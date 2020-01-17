<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterArticlesRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $params = config('project.params');
        $filters = implode(',', array_keys(config('project.filters.article')));
        return [
            $params['filter'] => "in:{$filters}",                      // Query scope filter
            $params['limit']  => 'size:1,10',                          // PerPage
            $params['sort']   => 'in:created_at,view_count,created',   // Sort: Age(created_at), View(view_count)
            $params['order']  => 'in:asc,desc',                        // Direction: Ascending or Descending
            $params['search'] => 'alpha_dash',                         // Search query
            $params['page']   => '',                                   // Page number
        ];
    }

    // public function rules()
    // {
    //     return [
    //         'f' => 'in:nocomment,notsolved',   // filter
    //         's' => 'in:created_at,view_count', // Sort: Age(created_at), View(view_count)
    //         'd' => 'in:asc,desc',              // Direction: Ascending or Descending
    //         'q' => 'alpha_dash',               // Search query
    //     ];
    // }
}