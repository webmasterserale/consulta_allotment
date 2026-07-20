<?php

namespace App\Filament\Resources\Allotments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AllotmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('unidad'),
                TextEntry::make('entra')
                    ->date(),
                TextEntry::make('sale')
                    ->date(),
                TextEntry::make('JUNTO'),
                TextEntry::make('NOMBRE'),
                TextEntry::make('CONFIRMA'),
                TextEntry::make('USUARIO'),
                TextEntry::make('CREACION')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('MODIFICA')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('HOTEL')
                    ->numeric(),
                TextEntry::make('PTS'),
                TextEntry::make('estado'),
                TextEntry::make('nombre_antes'),
                TextEntry::make('USUARIO2'),
                TextEntry::make('tipo'),
                TextEntry::make('observa')
                    ->columnSpanFull(),
                TextEntry::make('bitacora')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('reserva')
                    ->numeric(),
                TextEntry::make('numcon')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('trading'),
                TextEntry::make('modi'),
                TextEntry::make('fe1')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('fe2')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('fe3')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('fe4')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('fe5')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('fe6')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('fe7')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('visible_web'),
                TextEntry::make('update_estado')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('id_solires')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('confirma_ant')
                    ->placeholder('-'),
            ]);
    }
}
