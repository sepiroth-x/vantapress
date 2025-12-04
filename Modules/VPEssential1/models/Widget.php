<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Widget extends Model
{
    protected $table = 'vp_widgets';
    
    protected $fillable = [
        'widget_area_id',
        'title',
        'type',
        'content',
        'settings',
        'order',
        'is_active',
    ];
    
    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];
    
    public function widgetArea(): BelongsTo
    {
        return $this->belongsTo(WidgetArea::class);
    }

    /**
     * Move widget up in order
     */
    public function moveUp(): void
    {
        $previousWidget = static::where('widget_area_id', $this->widget_area_id)
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousWidget) {
            $tempOrder = $this->order;
            $this->order = $previousWidget->order;
            $previousWidget->order = $tempOrder;

            $this->save();
            $previousWidget->save();
        }
    }

    /**
     * Move widget down in order
     */
    public function moveDown(): void
    {
        $nextWidget = static::where('widget_area_id', $this->widget_area_id)
            ->where('order', '>', $this->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextWidget) {
            $tempOrder = $this->order;
            $this->order = $nextWidget->order;
            $nextWidget->order = $tempOrder;

            $this->save();
            $nextWidget->save();
        }
    }
}
