<?php
    $rqry = "SELECT MIN(price) AS price, room_type, name, img, c.date AS date, cnt, person
        FROM reserve_check AS c
        INNER JOIN room AS r
        ON r.num = c.room_type
        WHERE 1=1
        AND r.state = 'Y'
        AND c.cnt > 0";
    if(date("H") > "18") {
        $rqry .= " and c.date >= '".date("Y-m-d", strtotime(date("Y-m-d")." + 1 days"))."'";
    } else {
        $rqry .= " and c.date >= '".date("Y-m-d")."'";
    }
    $rqry .= " GROUP BY room_type";
    $rres = mysqli_query($dbconn, $rqry);
    $room_arr = array();
    while($rrow = mysqli_fetch_array($rres)) {

        $book_button = ($rrow["cnt"] > 0) ? "최저가 예약하기" : "Sold Out";
        $book_css = ($rrow["cnt"] > 0) ? "" : " sold_out";
        $person = ($rrow["person"] == 0 || $rrow["person"] == "") ? "" : " (".$rrow["person"]."인)";
?>
<div class="col-lg-3 col-sm-6">
    <div class="accomodation_item text-center">
        <div class="hotel_img">
            <img src="<?=$rrow['img']?>" alt="">
            <a href="javascript:reserve.booking_state('<?=$rrow['room_type']?>', '<?=$rrow['date']?>', 'booking_select', 'html')" class="btn theme_btn button_hover<?=$book_css?>"><?=$book_button?></a>
        </div>
        <a href="#"><h4 class="sec_h4"><?=$rrow["name"].$person?></h4></a>
        <h5><?=won?> <?=@number_format($rrow["price"])?><small>/night</small></h5>
        <h6><?=$rrow["date"]?></h6>
    </div>
</div>
<?php
    }
?>