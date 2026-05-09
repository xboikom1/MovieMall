<?php

namespace App\Http\Middleware;

use App\Models\CartItem;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyncSessionCart
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $sessionCart = session('cart', []);

            if (!empty($sessionCart)) {
                foreach ($sessionCart as $reqItem) {
                    $type        = $reqItem['type'];
                    $referenceId = $reqItem['reference_id'];
                    $opts        = $reqItem['options'] ?? [];

                    $query    = CartItem::where(['user_id' => Auth::id(), 'type' => $type, 'reference_id' => $referenceId]);
                    $existing = null;

                    if ($type === 'ticket') {
                        foreach ($query->get() as $dbItem) {
                            $dbOpts = is_string($dbItem->options) ? json_decode($dbItem->options, true) : $dbItem->options;
                            if (($dbOpts['date'] ?? '') === ($opts['date'] ?? '') &&
                                ($dbOpts['time'] ?? '') === ($opts['time'] ?? '')) {
                                $existing = $dbItem;
                                break;
                            }
                        }
                    } else {
                        if (empty($opts)) {
                            $query->where(fn($q) => $q->whereNull('options')
                                ->orWhereRaw("options::text = '[]'")->orWhereRaw("options::text = '{}'"));
                        } else {
                            $query->whereJsonContains('options', $opts);
                        }
                        $existing = $query->first();
                    }

                    if ($existing) {
                        if ($type === 'ticket') {
                            $existingOpts             = is_string($existing->options) ? json_decode($existing->options, true) : ($existing->options ?? []);
                            $merged                   = array_values(array_unique(array_merge($existingOpts['seat_ids'] ?? [], $opts['seat_ids'] ?? [])));
                            $existingOpts['seat_ids'] = $merged;
                            $existing->options        = $existingOpts;
                            $existing->quantity       = count($merged);
                            $existing->save();
                        } else {
                            $existing->increment('quantity', $reqItem['quantity'] ?? 1);
                        }
                    } else {
                        CartItem::create([
                            'user_id'      => Auth::id(),
                            'type'         => $type,
                            'reference_id' => $referenceId,
                            'quantity'     => $reqItem['quantity'] ?? 1,
                            'options'      => empty($opts) ? null : $opts,
                        ]);
                    }
                }

                session()->forget('cart');
            }
        }

        return $next($request);
    }
}
