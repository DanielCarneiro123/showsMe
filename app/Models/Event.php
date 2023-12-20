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
        return $this->hasMany(Comment::class, 'event_id')
                    ->orderByRaw("author_id = ? DESC", [auth()->user()->user_id])
                    ->orderByDesc('likes');
    }
    

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'event_id', 'event_id');
    }
    public function getAverageRatingAttribute()
    {
        $ratings = $this->ratings;
        $totalRatings = $ratings->count();

        if ($totalRatings > 0) {
            $sum = $ratings->sum('rating');
            return $sum / $totalRatings;
        }

        return 0; 
    }
    public function userRating()
    {
        if (auth()->check()) {
            $userId = auth()->user()->user_id;

            return $this->ratings()->where('author_id', $userId)->first();
        }

        return null;
    }
    public function soldTickets()
    {
        return TicketInstance::whereIn('ticket_type_id', $this->ticketTypes->pluck('ticket_type_id'))
            ->get();
    }

    public function getTotalSoldTickets()
    {
        return $this->soldTickets()->count();
    }

    public function tickets_chart()
    {
        // Obter todas as instâncias de ingressos relacionadas aos tipos de ingressos do evento
        $ticketInstances = TicketInstance::whereIn('ticket_type_id', $this->ticketTypes->pluck('ticket_type_id'))->get();
    
        // Agrupar as instâncias de ingressos por data
        $dataByDate = $ticketInstances->groupBy(function ($item) {
            return $item->order->timestamp->format('Y-m-d');
        });
    
        // Preparar dados para o gráfico
        $labels = $dataByDate->keys()->sort()->toArray();
    
        $datasets = [];
        
        // Paleta de cores para os gráficos de linha
        $colorPalette = [ '#ff7f0e', '#2ca02c', '#d62728', '#9467bd', '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf', '#FF5733', '#33FF57', '#5733FF', '#FF33ED', '#FF3371'];
    
        foreach ($this->ticketTypes as $key => $ticketType) {
            $typeData = $dataByDate->map(function ($items) use ($ticketType) {
                return $items->where('ticket_type_id', $ticketType->ticket_type_id)->count();
            })->values()->toArray();
    
            // Adicione cores da paleta de forma circular
            $colorIndex = $key % count($colorPalette);
    
            $datasets[] = [
                'label' => $ticketType->name,
                'data' => $typeData,
                'borderWidth' => 1,
                'backgroundColor' => $colorPalette[$colorIndex], // Use a mesma cor para preenchimento
                'borderColor' => $colorPalette[$colorIndex],
            ];
        }
    
        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }
    

    public function all_tickets_chart()
{
    // Obter todas as instâncias de ingressos relacionadas aos tipos de ingressos do evento
    $ticketInstances = TicketInstance::whereIn('ticket_type_id', $this->ticketTypes->pluck('ticket_type_id'))->get();

    // Agrupar as instâncias de ingressos por data
    $dataByDate = $ticketInstances->groupBy(function ($item) {
        return $item->order->timestamp->format('Y-m-d');
    });

    // Preparar dados para o gráfico
    $labels = $dataByDate->keys()->sort()->toArray();

    // Inicializar array para armazenar a soma total de bilhetes vendidos por dia
    $totalTicketsData = [];

    foreach ($labels as $date) {
        // Somar a quantidade de bilhetes vendidos para cada tipo no mesmo dia
        $totalTicketsData[] = $dataByDate[$date]->count();
    }

    // Paleta de cores para os gráficos de linha
    $colorPalette = ['#1f77b4'];

    // Criar dataset para representar a quantidade total de bilhetes vendidos
    $datasets[] = [
        'label' => 'Total de Bilhetes Vendidos',
        'data' => $totalTicketsData,
        'borderWidth' => 1,
        'backgroundColor' => $colorPalette[0], // Use a mesma cor para preenchimento
        'borderColor' => $colorPalette[0],
    ];

    return [
        'labels' => $labels,
        'datasets' => $datasets,
    ];
}

    

    public function tickets_pie_chart()
    {
        // Recuperar todas as instâncias de bilhetes
        $ticketInstances = TicketInstance::whereIn('ticket_type_id', $this->ticketTypes->pluck('ticket_type_id'))->get();
    
        // Contar o número total de bilhetes
        $totalTickets = $ticketInstances->count();
    
        // Definir a paleta de cores
        $colorPalette = ['#ff7f0e', '#2ca02c', '#d62728', '#9467bd', '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf', '#FF5733', '#33FF57', '#5733FF', '#FF33ED', '#FF3371'];
    
        // Se houver mais tipos de bilhetes do que cores na paleta, gere cores adicionais
        while (count($this->ticketTypes) > count($colorPalette)) {
            // Adicione cores adicionais à paleta (pode ser gerado de forma dinâmica)
            $colorPalette[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        }
    
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
    
            // Adicionar cor ao array
            $data['datasets'][0]['backgroundColor'][] = array_shift($colorPalette);
        }
    
        return $data;
    }
    
    public function per_sold_tickets_pie_chart($ticketTypeId)
    {
        // Recuperar instâncias de bilhetes para o tipo atual
        $ticketType = TicketType::findOrFail($ticketTypeId);    
        $typeTicketInstances = TicketInstance::where('ticket_type_id', $ticketType->ticket_type_id)->get();
    
        // Contar o número de bilhetes vendidos e o estoque
        $ticketsSold = $typeTicketInstances->count();
        $stock = $ticketType->stock;
    
        // Calcular a porcentagem preenchida
        $percentageFilled = ($ticketsSold * 100) / ($stock + $ticketsSold);
    
        // Preparar dados para o gráfico

        $colorPalette = [ '#ff7f0e', '#2ca02c', '#d62728', '#9467bd', '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf', '#FF5733', '#33FF57', '#5733FF', '#FF33ED', '#FF3371'];



        $pieChartData = [
            'label' => $ticketType->name,
            'data' => [
                'labels' => [$ticketType->name],
                'datasets' => [
                    [
                        'data' => [$percentageFilled, 100 - $percentageFilled],
                        'backgroundColor' => [
                            '#1f77b4', // Color for percentage of tickets sold
                            '#ffffff', // Color for percentage of stock remaining (white)
                        ],
                        'borderColor' => '#1f77b4', // Border color for the chart
                        'borderWidth' => 1, // Border width for the chart
                        'hoverOffset' => 4,
                    ],
                ],
            ],
        ];
    

        return $pieChartData;
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

    public static function countEventsByMonth($month)
    {
        try {
            $count = self::whereMonth('start_timestamp', $month)->count();
            return $count;
        } catch (\Exception $e) {
            echo '<script>console.error("countEventsByMonth - erro ao contar eventos: ' . $e->getMessage() . '");</script>';
            return 0; // Ou outro valor padrão apropriado
        }
    }

    public static function countEventsByDay($day)
    {
        try {
            $count = self::whereDay('start_timestamp', $day)->count();
            return $count;
        } catch (\Exception $e) {
            echo '<script>console.error("countEventsByDay - erro ao contar eventos: ' . $e->getMessage() . '");</script>';
            return 0; // Ou outro valor padrão apropriado
        }
    }

    public static function countEventsByYear($year)
    {
        try {
            $count = self::whereYear('start_timestamp', $year)->count();
            return $count;
        } catch (\Exception $e) {
            echo '<script>console.error("countEventsByYear - erro ao contar eventos: ' . $e->getMessage() . '");</script>';
            return 0; // Ou outro valor padrão apropriado
        }
    }


    public function images()
    {
        return $this->hasMany(EventImage::class, 'event_id');
    }
}
