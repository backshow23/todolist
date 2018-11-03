<?php
$list = array();
foreach ($work as $item){
    $color = '';
    if($item->status==3){
        $color = "#02c44d";
    }elseif($item->status==2){
        $color = "#31afdf";
    }else{
        $color = "#f73446";
    }
    $list[] = array(
        'id'    => $item->id,
        'title' => $item->work_name,
        'start' => $item->starting_date,
        'end'   => $item->ending_date,
        'status'=> $item->status,
        'color' => $color,
    );
};
echo json_encode($list);