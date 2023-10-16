<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\PostView;
use App\Models\PostVote;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class PostOverview extends Widget
{
    protected static string $view = 'filament.widgets.post-overview';

    protected int | string | array $columnSpan = 3;

    public ?Model $record = null;

    protected function getViewData(): array
    {
        return [
            'views' => PostView::where('post_id', $this->record->id)->count(),
            'upvotes' => PostVote::where('post_id', $this->record->id)->where('upvote', 1)->count(),
            'downvotes' => PostVote::where('post_id', $this->record->id)->where('upvote', 0)->count(),
        ];
    }
}
