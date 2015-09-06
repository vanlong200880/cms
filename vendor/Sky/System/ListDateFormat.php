<?php
namespace Sky\System;
class ListDateFormat{
    public function listDay($min = 1, $max = 31, $active = ''){
        $day = array();
        $day[] = '<option value="">-- Ngày --</option>';
        for($i = $min; $i <= $max; $i++){
            if($i == $active){
                $day[] = '<option selected value="'.$i.'">'.$i.'</option>';
            }else{
                $day[] = '<option value="'.$i.'">'.$i.'</option>';
            }
            
        }
        return $day;
    }
    public function listMonth($min = 1, $max = 12, $active = ''){
        $month = array();
        $month[] = '<option value="">-- Tháng --</option>';
        for($i = $min; $i <= $max; $i++){
            if($i == $active){
                $month[] = '<option selected value="'.$i.'">'.$i.'</option>';
            }else{
                $month[] = '<option value="'.$i.'">'.$i.'</option>';
            }
        }
        return $month;
    }
    
    public function listYear($min = 1, $max = 2015, $active = ''){
        $year = array();
        $year[] = '<option value="">-- Năm --</option>';
        for($i = $max; $i >= $min; $i--){
            if($i == $active){
                $year[] = '<option selected value="'.$i.'">'.$i.'</option>';
            }else{
                $year[] = '<option value="'.$i.'">'.$i.'</option>';
            }
        }
        return $year;
    }
}

