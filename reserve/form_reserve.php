            <form name="book_form">
            <input type="hidden" name="mode" value="price">
            <div class="container">
                <div class="row hotel_booking_table">
                    <div class="col-md-3">
                        <h2>Book<br> Your Room</h2>
                    </div>
                    <div class="col-md-9">
                        <div class="boking_table">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="book_tabel_item">
                                        <div class="form-group">
                                            <div class='input-group date' id='datetimepicker11'>
                                                <input type='text' class="form-control datepicker" placeholder="Arrival Date" name="sdate" onchange="reserve.booking_form('book_form','html')"/>
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' class="form-control datepicker" placeholder="Departure Date" name="edate" onchange="reserve.booking_form('book_form','html')"/>
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="book_tabel_item">
                                        <!--<div class="input-group">
                                            <select class="wide" name="child">
                                                <option data-display="Child">Child</option>
                                                <?php 
                                                    for($i=1; $i<10; $i++) { 
                                                        echo "<option value='".$i."'>0".$i."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>-->
                                        <div class="input-group">
                                            <select class="wide" name="room_type" onchange="reserve.booking_form('book_form','html')">
                                                <option data-display="Room Type" value="">Room Type</option>
                                                <?php
                                                    $room_qry = "select num, name from room where state = 'Y' order by num asc";
                                                    $room_res = mysqli_query($dbconn, $room_qry);
                                                    while($room_row = mysqli_fetch_array($room_res)) {
                                                ?>
                                                <option value="<?=$room_row["num"]?>"><?=$room_row["name"]?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="input-group">
                                            <select class="wide" name="room_cnt" onchange="reserve.booking_form('book_form','html')">
                                                <option data-display="number of room" value="0">number of room</option>
                                                <?php 
                                                    for($i=1; $i<10; $i++) { 
                                                        echo "<option value='".$i."'>0".$i."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="book_tabel_item">
                                        <a id="book_now" class="book_now_btn button_hover" href="javascript:reserve.booking();">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row hotel_booking_table" id="reserve_detail">
                    <div class="col-md-3">
                        
                    </div>
                    <div class="col-md-9">
                        <div class="boking_table">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="book_tabel_item">
                                        <div class="form-group">
                                            <div class='input-group' id=''>
                                                <input type='text' class="form-control" placeholder="Booker" name="reserve_name"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class='input-group' id=''>
                                                <input type='text' class="form-control" placeholder="Phone Number" name="phone" numberOnly maxlength="11" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class='input-group' id=''>
                                                <input type='password' class="form-control" placeholder="Password" name="password" maxlength="20" />
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="book_tabel_item">
                                        <div class="form-group">
                                            <div class='input-group reserve_ment' id=''>
                                                <?php
                                                    switch(date("w", date("Y-m-d"))) {
                                                        case 5:
                                                            $show_date = date("Y-m-d", strtotime(date("Y-m-d")." + 3days"));
                                                        break;
                                                        case 6:
                                                            $show_date = date("Y-m-d", strtotime(date("Y-m-d")." + 2days"));
                                                        break;
                                                        default;
                                                            $show_date = date("Y-m-d", strtotime(date("Y-m-d")." + 1days"));
                                                        break;
                                                    }
                                                ?>
                                                <p>
                                                    익일 영업일까지(<?=date("Y-m-d", strtotime(date("Y-m-d")." + 1days"))?> 18:00 PM) 미입금시 예약이 취소 될 수 있으며, 
                                                    <br>명절 및 휴일은 확인이 지연 될 수 있습니다.
                                                    <br><br>
                                                    xx은행 : 입금자 이준혁
                                                </p>
                                                
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class='input-group reserve_ment'>
                                                <p class="reserve_pay">
                                                   <?=won?> <b>1000</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>