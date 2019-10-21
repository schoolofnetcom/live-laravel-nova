<?php

namespace App\Nova;

use App\Nova\Lenses\PostCommentLens;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Comment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Comment';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'content';


    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'content' , 'posts.title'
    ];

    public static function label()
    {
        return 'Comentários';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Textarea::make('Conteúdo', 'content')
                ->showOnIndex()
                ->displayUsing(function($value){
                    return substr($value,0,20). '...';
                }),
            BelongsTo::make('Post')
                ->showOnCreating()
                ->searchable()
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->join('posts', 'posts.id', '=', 'comments.post_id');
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [
            new PostCommentLens()
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
