<?php

namespace App\DataTables;

use App\Models\Carro;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CarrosDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query->with(['marca'])))
        ->addColumn('action', 'carro.action')
        ->rawColumns(['link', 'action'])
        ->editColumn('cor',function($r){
            return strtoupper($r->cor);
        })
            ->editColumn('created_at', function ($r) {
                return Carbon::parse($r->created_at)->format('d/m/Y');
            })

            ->editColumn('updated_at', function ($r) {
                return Carbon::parse($r->updated_at)->format('d/m/Y');
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Carro $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Carro $model): QueryBuilder
    {
        return $model->newQuery()->where('responsavel_id', '=', Auth::id());
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('carros-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->responsive(true)
                    ->buttons([
                        // Button::make('excel'),
                        // Button::make('csv'),
                        // Button::make('pdf'),
                        Button::make('print')->text('<i class="fa fa-print"></i>'),
                        Button::make('reset')->text('<i class="fa fa-sync"></i>'),
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [

            Column::make('modelo')->title('Modelo'),
            Column::make('cor'),
            Column::make('placa' )->title('Placa'),
            Column::make('kilometragem', )->title('Kilometragem'),
            Column::make('marca.nome', )->title('Marca'),
            Column::make('descricao')->title('Descri????o'),
            Column::make('created_at')->title('Criado em'),
            Column::make('updated_at')->title('Atualizado em'),
            Column::computed('action')->title('A????es')
            ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Carros_' . date('dmY');
    }
}
