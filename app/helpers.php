<?php

function pagination($request, $data, $limit = 10)
{
    $page = $request->page;
    $total = (clone $data)->count();
    $offset = ($page - 1) * $limit;
    $total_page = ceil($total / $limit);
    $data = compact('limit', 'total', 'offset', 'total_page');
    return $data;
}

function pageInfo($pagination, $total_current)
{
    $data = [
        'offset' => $pagination['offset'],
        'limit' => $pagination['limit'],
        'total' => $pagination['total'],
        'total_page' => $pagination['total_page'],
        'total_current' => $total_current,
    ];
    return $data;
}

function months()
{
    return [
        'January' => 1,
        'February' => 2,
        'March' => 3,
        'April' => 4,
        'May' => 5,
        'June' => 6,
        'July' => 7,
        'August' => 8,
        'September' => 9,
        'October' => 10,
        'November' => 11,
        'December' => 12,
    ];
}
