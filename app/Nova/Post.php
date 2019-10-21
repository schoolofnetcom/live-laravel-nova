<?php

namespace App\Nova;

use App\Nova\Filters\PostFilter;
use App\Nova\Lenses\MostCommentablePostsLens;
use App\Nova\Lenses\PostCommentLens;
use App\Nova\Metrics\CommentsCountMetric;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Nova;
use NovaButton\Button;

class Post extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Post';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = ['categories:id,name'];

    public static function newModel()
    {
        $model = parent::newModel();
        $model->user_id = auth('admin')->user()->id;

        return $model;
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

            Text::make('Título', 'title')
                ->sortable()
                ->rules('required', 'max:255'),

            NovaTinyMCE::make('Conteúdo', 'content')
                ->rules('required')
                ->hideFromIndex(),

            BelongsTo::make('Usuário', 'user', User::class)
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            BelongsToMany::make('Categorias', 'categories', Category::class)
                ->rules('exists:categories,id'),

            Button::make('Comentário')
                ->lens('App\Nova\Comment', 'post-comment')
                ->withFilters([
                    PostFilter::class => $this->resource->id
                ])
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            (new CommentsCountMetric())->onlyOnDetail(),
        ];
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
            new MostCommentablePostsLens(),
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
        return [

        ];
    }
}
