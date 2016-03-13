<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container">
    <div class="title"><h1><?=$title ?></h1></div>
    <?php echo validation_errors(); ?>

    <div>
        <?php echo form_open('book/addBook'); ?>
            <div class="form-group">
                <label for="allusers">Select a user</label>
                <?php
                    $bookallusers = array(
                        'name'      => 'allusers',
                        'id'        => 'allusers',
                        'placeholder' => 'Type a user name',
                        'class'     =>  'form-control input-small'
                    );
                    $hideUser = array(
                        'name'      => 'hideUser',
                        'id'        => 'hideUser',
                        'type'      => 'hidden'
                    );
                ?>
                <?php echo form_input($bookallusers); ?>
                <?php echo form_input($hideUser); ?>
            </div>
            <div class="form-group">
                <label for="user">Select a room</label>
                <?php $bookrooms = array(
                    'name'      => 'rooms',
                    'id'        => 'rooms',
                    'placeholder' => 'Type a room name',
                    'class'     =>  'form-control input-small'
                );
                    $hideRoom = array(
                        'name'  => 'hideRoom',
                        'id'    => 'hideRoom',
                        'type'  => 'hidden'
                    );
                ?>
                <?php echo form_input($bookrooms); ?>
                <?php echo form_input($hideRoom); ?>
            </div>
            <div class="form-group bootstrap-timepicker timepicker">
                <div class="col-md-12">
                    <label for="bookdate">Start From</label>
                </div>
                <div class="input-append date input-group bootstrap-timepicker timepicker col-md-6"  style="float:left;"
                     id="bookdate"
                     data-date-format="dd-mm-yyyy">
                    <?php $bookdateData = array(
                            'name'  => 'bookdate',
                            'id'    => 'bookdate',
                            'value' => date('d-m-Y'),
                            'class' =>  'form-control input-small'
                    ); ?>
                    <?php echo form_input($bookdateData); ?>
                    <span class="input-group-addon"><i class="icon-calendar glyphicon glyphicon-calendar"></i></span>
                </div>

                <div class="input-group bootstrap-timepicker timepicker col-md-6" style="float:left;">
                    <input name="booktime" type="text" class="form-control input-small" id="booktime" placeholder="">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </div>
            <div class="form-group">
                <label for="bookuni">Booking Hours</label>
                <input name="bookuni" type="number" class="form-control" id="bookuni" placeholder="Hours" max="10" min="1" onclick="changeMaxhour()">
            </div>
            <button type="reset" class="btn btn-default">Reset</button>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
<script type="text/javascript">
    $(function() {
        $('#allusers').autocomplete({
            source: "<?php echo site_url('book/getKeyusers'); ?>",
            select: function(event, ui) {
                $('#hideUser').val(ui.item.id);
                console.log(ui.item.id);

                var user_id = ui.item.id;

                $('#rooms').autocomplete({
                    source: "<?php echo site_url('book/getKeywordUserRooms'); ?>" + "/" + user_id,
                    select: function(revent, rui) {
                        $('#hideRoom').val(rui.item.id);
                        console.log(rui.item.id);
                        console.log($('#rooms').val());
                    }
                });
            }
        });
    });

    /*
    * from the set start time and room open time to caculate the max hour can be
    * book for today
     */
    var changeMaxhour = function() {
        var roomNum = $('#hideRoom').val();
        var bookHour = $('#booktime').val();
        if (roomNum != '' && bookHour) {
            $.ajax({
                url: "<?php echo site_url('book/getMaxHour'); ?>",
                data: {'roomNum':roomNum, 'bookHour': bookHour},
                dataType: "json",
                type: "POST",
                success: function(data) {
                    $('#bookuni').attr('max', data.bookUni);
                }
            });
        }
    }

    var createBook = function() {
        $.ajax({

        });
    }
</script>