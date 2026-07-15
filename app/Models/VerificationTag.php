<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class VerificationTag extends Model
{
    protected $fillable = [
        'label',
        'type',
        'attribute_key',
        'attribute_value',
        'content',
        'code',
        'script_attributes',
        'placement',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const TYPE_META = 'meta';
    public const TYPE_SCRIPT = 'script';
    public const TYPE_CUSTOM = 'custom';

    public const PLACEMENTS = [
        'head_start',
        'head_end',
        'body_start',
        'body_end',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function renderTag(): HtmlString
    {
        switch ($this->type) {
            case self::TYPE_META:
                $attrKey = $this->attribute_key ?: 'name';
                $attrValue = e($this->attribute_value ?? '');
                $content = e($this->content ?? '');
                return new HtmlString("<meta {$attrKey}=\"{$attrValue}\" content=\"{$content}\">");

            case self::TYPE_SCRIPT:
                $attributes = trim($this->script_attributes ?? '');
                $attrString = $attributes ? ' ' . $attributes : '';
                $code = $this->code ?? '';
                return new HtmlString("<script{$attrString}>\n{$code}\n</script>");

            default:
                return new HtmlString($this->code ?? '');
        }
    }

    public function getSnippetAttribute(): ?string
    {
        if (!empty($this->code)) {
            return $this->code;
        }

        if ($this->type === self::TYPE_META && $this->attribute_key && $this->attribute_value) {
            $attrKey = $this->attribute_key;
            $attrValue = $this->attribute_value;
            $content = $this->content;
            return "<meta {$attrKey}=\"{$attrValue}\" content=\"{$content}\">";
        }

        if ($this->type === self::TYPE_SCRIPT && $this->content) {
            return "<script>{$this->content}</script>";
        }

        return null;
    }
}

