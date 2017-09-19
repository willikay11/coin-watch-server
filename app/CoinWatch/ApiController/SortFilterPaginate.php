<?php

/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/19/17
 * Time: 7:55 AM
 */
namespace App\CoinWatch\ApiController;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

trait SortFilterPaginate
{
    /*
     * Set the default per page value
     */
    protected $perPage = 10;

    /*
     * Setup the tablestates array
     */
    protected $stateArray = [
        'query' => null,
        'sortDirection' => 'desc',
        'sortItem' => 'id',
        'page' => 1,
        'per_page' => 10
    ];

    protected $filterArray = [];
    protected $specialFilterArray = [];
    protected $relationTables = [];

    /*
     * Get the sort, filter and paginate data from the input
     */
    public function getTableStateData()
    {
        $state = Input::all();

        //Get the search query if any
        if(isset($state['search']) && $state['search'] != '')
        {
            $this->stateArray['query'] = $state['search'];
        }

        //Get the Sort Parameters
        if(isset($state['sort']) && $state['sort'] != '')
        {
            $sortArray = explode('|', $state['sort']);

            $this->stateArray['sortDirection'] = $sortArray[1];

            $this->stateArray['sortItem'] = $sortArray[0];
        }

        //Get the pagination parameters
        if(isset($state['page']))
        {
            $this->stateArray['page'] = $state['page'];
        }

        if(isset($state['per_page']))
        {
            $this->stateArray['per_page'] = $state['per_page'];
        }
    }

    /*
     * Filter, Sort and Paginate the Model
     */
    public function sortFilterPaginate($model, $filterParams = [], $transformer, $filterFunction = null, $per_page = null, $sortItem = null)
    {
        if(!is_null($per_page))
        {
            $this->stateArray['per_page'] = $per_page;
        }

        if(!is_null($sortItem))
        {
            $this->stateArray['sortItem'] = $sortItem;
        }

        $this->getTableStateData();

        $this->filterArray = $filterParams;

        $filtered = $this->filter($model, $filterFunction);

        return $this->paginate($this->sort($filtered)->get(), $transformer);
    }

    /*
     * Do the filter for the model
     */
    public function filter($model, $filterFunction)
    {
        if(! is_null($this->stateArray['query']))
        {
            $model = $model->search($this->stateArray['query']);
        }

        if($filterFunction)
        {
            $model = $filterFunction($model);
        }

        foreach($this->filterArray as $filter)
        {
            if(! isset($filter['sign']))
            {
                $filter['sign'] = '=';
            }

            $model = $model->where($filter['column'], $filter['sign'] , $filter['value']);
        }

        return $model;
    }

    /*
     * Do the sort on the model
     */
    public function sort($model)
    {
        return $model->orderBy($this->stateArray['sortItem'], $this->stateArray['sortDirection']);
    }

    /*
     * Paginate the data
     */
    public function paginate($collection, $transformer)
    {
        $offset = ($this->stateArray['page'] * $this->stateArray['per_page']) - $this->stateArray['per_page'];

        $slicedData = $collection->slice($offset, $this->stateArray['per_page']);

        $transformedData = $slicedData->map($transformer);

        return new LengthAwarePaginator($transformedData, $collection->count(), $this->stateArray['per_page'], $this->stateArray['page']);
    }

    /*
     * Setup a universal transformer
     */
    public function universalTransformer()
    {
        return function($model)
        {
            return $model->toArray();
        };
    }
}