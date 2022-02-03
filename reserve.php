<?php
    $page = "reserve";
    include "./common/top.php";
?>      
        <!--================ Accomodation Area  =================-->
        <section class="accomodation_area section_gap">
            <div class="container">
                <div class="section_title text-center">
                    <h2 class="title_color">Special Accomodation</h2>
                    <p>We all live in an age that belongs to the young at heart. Life that is becoming extremely fast,</p>
                </div>
                <div class="row mb_30">
                    <?php
                        include "./reserve/special_reserve.php";
                    ?>
                </div>
            </div>
        </section>
        <!--================ Accomodation Area  =================-->
        <!--================Booking Tabel Area =================-->
        <section class="hotel_booking_area">
            <?php
                include "./reserve/form_reserve.php";
            ?>
        </section>
        <!--================Booking Tabel Area  =================-->
        <!--================ Accomodation Area  =================-->
        <section class="accomodation_area section_gap">
            <div class="container">
                <div class="section_title text-center">
                    <?php
                    //객실수 많은애들부터 털기
                    $normal_qry = "select * from reserve_check where 1=1 ";
                    $normal_qry .= " and cnt > 0 and price > 0 ";
                    $normal_qry .= " and date >= now()";
                    $normal_qry .= " order by cnt desc, date, price asc";
                    $normal_qry .= " limit 8";
                    $normal_res = mysqli_query($dbconn, $normal_qry);

                    ?>
                    <h2 class="title_color">Normal Accomodation</h2>
                    <p>We all live in an age that belongs to the young at heart. Life that is becoming extremely fast,</p>
                </div>
                <div class="row accomodation_two">
                    <?php
                        $ncnt = mysqli_num_rows($normal_res);
                        if($ncnt > 0) {
                            $room_qry = "select * from room where 1=1 ";
                            $room_res = mysqli_query($dbconn, $room_qry);
                            $room = array();
                            while($room_row = mysqli_fetch_array($room_res)) {
                                $room[$room_row["num"]]["name"] = $room_row["name"];
                                $room[$room_row["num"]]["img"] = $room_row["img"];
                                $room[$room_row["num"]]["state"] = $room_row["state"];
                            }

                            while($nrow = mysqli_fetch_array($normal_res)) {
                                $css = ($room[$nrow["room_type"]]["state"] != "Y") ? " sold_out" : "";
                    ?>
                    <div class="col-lg-3 col-sm-6">
                        <div class="accomodation_item text-center">
                            <div class="hotel_img">
                                <img src="<?=$room[$nrow['room_type']]["img"]?>" alt="">
                                <a href="javascript:reserve.booking_state('<?=$nrow['room_type']?>', '<?=$nrow['date']?>', 'booking_select', 'html')" class="btn theme_btn button_hover<?=$css?>">Book Now</a>
                            </div>
                            <a href="#"><h4 class="sec_h4"><?=$room[$nrow["room_type"]]["name"]?></h4></a>
                            <h5><?=won?> <?=@number_format($nrow["price"])?><small>/night</small></h5>
                            <h6><?=$nrow["date"]?></h6>
                        </div>
                    </div>
                    <?php        
                            }
                        }
                    ?>
                </div>
            </div>
        </section>
        <!--================ Accomodation Area  =================-->
<?php
    include "./common/footer.php";
?>