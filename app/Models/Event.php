<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event_';
    public $timestamps = false;
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'name',
        'location',
        'description',
        //'private', //acho que não é atribuido em grande quantidade por isso não deve estar aqui (?)
        'start_timestamp',
        'end_timestamp',
        'creator_id',
    ];

    protected $casts = [
        'private' => 'boolean',
        'start_timestamp' => 'datetime',
        'end_timestamp' => 'datetime',
    ];

     
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class, 'event_id', 'event_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'event_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'event_id');
    }

    public function soldTickets()
    {
        return TicketInstance::whereIn('ticket_type_id', $this->ticketTypes->pluck('ticket_type_id'))
            ->get();
    }


    /*public function tickets_chart()
    {
        $ticketInstances = TicketInstance::whereIn('ticket_type_id', $this->ticketTypes->pluck('ticket_type_id'))->get();
    
        // Agrupar os dados por data
        $dataByDate = $ticketInstances->groupBy('purchase_date');
    
        // Calcular as datas desde o início do evento até o momento atual
        $eventStart = $this->start_timestamp;
        $eventEnd = now(); // ou use a data de término do evento se disponível
    
        // Calcular a diferença em dias
        $daysDifference = $eventEnd->diffInDays($eventStart);
    
        // Gerar o intervalo de datas
        $dateRange = [];
        for ($i = 0; $i <= $daysDifference; $i++) {
            $dateRange[] = $eventStart->addDays($i)->format('Y-m-d');
        }
    
        // Preparar dados para o gráfico
        $labels = $dateRange; // Dates as labels
        $datasets = [];
    
        foreach ($this->ticketTypes as $ticketType) {
            $typeData = [];
    
            foreach ($dateRange as $date) {
                $typeData[] = $dataByDate->has($date) ? $dataByDate[$date]->where('ticket_type_id', $ticketType->ticket_type_id)->count() : 0;
            }
    
            $datasets[] = [
                'label' => $ticketType->name,
                'data' => $typeData,
                'borderWidth' => 1,
            ];
        }
    
        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }
    */

    public function tickets_chart()
    {
        $ticketInstances = TicketInstance::whereIn('ticket_type_id', $this->ticketTypes->pluck('ticket_type_id'))
            ->get();
    
        // Agrupar os dados por data
        $dataByDate = $ticketInstances->groupBy('purchase_date');
    
        // Preparar dados para o gráfico
        $labels = $dataByDate->keys()->toArray(); // Dates as labels
        $datasets = [];
    
        foreach ($this->ticketTypes as $ticketType) {
            $typeData = $dataByDate->map(function ($items) use ($ticketType) {
                return $items->where('ticket_type_id', $ticketType->ticket_type_id)->count();
            })->values()->toArray();
    
            $datasets[] = [
                'label' => $ticketType->name,
                'data' => $typeData,
                'borderWidth' => 1,
            ];
        }
    
        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    public function tickets_pie_chart()
{
    // Recuperar todas as instâncias de bilhetes
    $ticketInstances = TicketInstance::whereIn('ticket_type_id', $this->ticketTypes->pluck('ticket_type_id'))
        ->get();

    // Contar o número total de bilhetes
    $totalTickets = $ticketInstances->count();

    // Definir a palete de cores
    $colorPalette = ['#593196', '#7d4a9a', '#a26aae', '#bf8fc4', '#d8b2da', '#f1e4f1'];

    // Preparar dados para o gráfico
    $data = [
        'labels' => [],
        'datasets' => [
            [
                'label' => 'My First Dataset',
                'data' => [],
                'backgroundColor' => [],
                'hoverOffset' => 4,
            ],
        ],
    ];

    foreach ($this->ticketTypes as $key => $ticketType) {
        // Contar o número de bilhetes para o tipo atual
        $typeCount = $ticketInstances->where('ticket_type_id', $ticketType->ticket_type_id)->count();

        // Calcular a porcentagem
        $percentage = ($totalTickets > 0) ? ($typeCount / $totalTickets) * 100 : 0;

        // Adicionar dados ao array
        $data['labels'][] = $ticketType->name;
        $data['datasets'][0]['data'][] = $percentage;

        // Calcular a posição na palete de cores com base na porcentagem
        $paletteIndex = floor(($percentage / 100) * (count($colorPalette) - 1));
        $data['datasets'][0]['backgroundColor'][] = $colorPalette[$paletteIndex];
    }

    return $data;
}


        public function tickets_pie_charts()
        {
            $pieChartsData = [];
        
            foreach ($this->ticketTypes as $key => $ticketType) {
                // Recuperar instâncias de bilhetes para o tipo atual
                $typeTicketInstances = TicketInstance::where('ticket_type_id', $ticketType->ticket_type_id)->get();
                
                // Contar o número de bilhetes vendidos e o estoque
                $ticketsSold = $typeTicketInstances->count();
                $stock = $ticketType->stock;
        
                // Calcular a porcentagem preenchida
                $percentageFilled = ($ticketsSold * 100) / ($stock + $ticketsSold);
        
                // Preparar dados para o gráfico
                $pieChartsData[] = [
                    'key' => $key,
                    'label' => $ticketType->name,
                    'data' => [
                        'labels' => [$ticketType->name, 'Remaining'],
                        'datasets' => [
                            [
                                'data' => [$percentageFilled, 100 - $percentageFilled],
                                'backgroundColor' => [
                                    '#593196', // Color for percentage of tickets sold
                                    '#a991d4', // Color for percentage of stock remaining
                                ],
                                'hoverOffset' => 4,
                            ],
                        ],
                    ],
                ];
            }
        
            return $pieChartsData;
        }

        public function calculateRevenue()
        {
            $totalRevenue = 0;

            // Iterar sobre os tipos de ingressos
            foreach ($this->ticketTypes as $ticketType) {
                // Filtrar instâncias de ingressos para o tipo atual
                $typeInstances = TicketInstance::where('ticket_type_id', $ticketType->ticket_type_id)->get();

                // Calcular o faturamento para o tipo atual e adicionar ao total
                $typeRevenue = $typeInstances->count() * $ticketType->price;
                $totalRevenue += $typeRevenue;
            }

            return $totalRevenue;
        }

        public static function countEvents()
        {
            try {
                $count = self::query()->count();
                return $count;
            } catch (\Exception $e) {
                echo '<script>console.error("countEvents - erro ao contar eventos: ' . $e->getMessage() . '");</script>';
                return 0; 
            }
        }

        public static function countActiveEvents()
        {
            $count = self::where('private', true)->count();
            return $count;
        }

        public static function countInactiveEvents()
        {
            $count = self::where('private', false)->count();
            return $count;
        }
}