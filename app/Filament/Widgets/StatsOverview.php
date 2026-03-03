<?php

namespace App\Filament\Widgets;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Lelang Aktif', Auction::where('status', 'active')->count())
                ->description('Barang yang sedang dilelang')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('success'),
            Stat::make('Bid Pending', Bid::where('status', 'pending')->count())
                ->description('Perlu persetujuan admin')
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color('warning'),
            Stat::make('Total Pengguna', User::count())
                ->description('User terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            Stat::make('Pendapatan', 'Rp ' . number_format(Transaction::where('status', 'completed')->sum('amount'), 0, ',', '.'))
                ->description('Total transaksi sukses')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
