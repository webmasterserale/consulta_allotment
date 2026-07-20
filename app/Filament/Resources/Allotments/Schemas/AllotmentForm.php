<?php

namespace App\Filament\Resources\Allotments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AllotmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('unidad')
                    ->required(),
                DatePicker::make('entra')
                    ->required(),
                DatePicker::make('sale')
                    ->required(),
                TextInput::make('JUNTO')
                    ->required(),
                TextInput::make('NOMBRE')
                    ->required(),
                TextInput::make('CONFIRMA')
                    ->required(),
                TextInput::make('USUARIO')
                    ->required(),
                DatePicker::make('CREACION'),
                DatePicker::make('MODIFICA'),
                TextInput::make('HOTEL')
                    ->required()
                    ->numeric(),
                TextInput::make('PTS')
                    ->required(),
                TextInput::make('estado')
                    ->required(),
                TextInput::make('nombre_antes')
                    ->required(),
                TextInput::make('USUARIO2')
                    ->required(),
                TextInput::make('tipo')
                    ->required(),
                Textarea::make('observa')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('bitacora')
                    ->numeric(),
                TextInput::make('reserva')
                    ->required()
                    ->numeric(),
                TextInput::make('numcon')
                    ->numeric(),
                TextInput::make('trading')
                    ->required(),
                TextInput::make('modi')
                    ->required(),
                DatePicker::make('fe1'),
                DatePicker::make('fe2'),
                DatePicker::make('fe3'),
                DatePicker::make('fe4'),
                DatePicker::make('fe5'),
                DatePicker::make('fe6'),
                DatePicker::make('fe7'),
                TextInput::make('visible_web')
                    ->required()
                    ->default('SI'),
                DateTimePicker::make('update_estado'),
                TextInput::make('id_solires')
                    ->numeric(),
                TextInput::make('confirma_ant'),
            ]);
    }
}
