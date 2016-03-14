<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div>
    <?php echo validation_errors(); ?>
    <div id="validation_message"></div>
    <div>
        <?php echo form_open('book/addBook', array('id'=>'addnewbookform', 'onsubmit'=>'createBook();return false;')); ?>
            <div class="form-group" id="userFormDiv">
                <label for="allusers">Select a user</label>
                <?php
                    $bookallusers = array(
                        'name'      => 'allusers',
                        'id'        => 'allusers',
                        'placeholder' => 'Type a user name',
                        'class'     =>  'form-control input-small'
                    );
                    $hideUser = array(
                        'name'      => 'user_id',
                        'id'        => 'user_id',
                        'type'      => 'hidden'
                    );
                ?>
                <?php echo form_input($bookallusers); ?>
                <?php echo form_input($hideUser); ?>
            </div>
            <div class="form-group" id="roomFormDiv">
                <label for="user">Select a room</label>
                <?php $bookrooms = array(
                    'name'      => 'rooms',
                    'id'        => 'rooms',
                    'placeholder' => 'Type a room name',
                    'class'     =>  'form-control input-small'
                );
                    $hideRoom = array(
                        'name'  => 'room_id',
                        'id'    => 'room_id',
                        'type'  => 'hidden'
                    );
                ?>
                <?php echo form_input($bookrooms); ?>
                <?php echo form_input($hideRoom); ?>
            </div>
            <div class="form-group bootstrap-timepicker timepicker">
                <div class="">
                    <label for="bookdate">Start From</label>
                </div>
                <div class="input-append date input-group bootstrap-timepicker timepicker col-md-6"
                     style="float:left;"
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

                <div class="input-group bootstrap-timepicker timepicker col-md-6" id="booktimeFormDiv" style="float:left;">
                    <input name="booktime" type="text" class="form-control input-small" id="booktime" placeholder="">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </div>
            <div class="form-group">
                <label for="bookuni">Booking Hours</label>
                <input name="bookuni" type="number" class="form-control" id="bookuni" placeholder="Hours" max="10" min="1" onclick="changeMaxhour()">
            </div>
            <div class="form-group">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-default" >Submit</button>
            </div>
        </form>
    </div>
    <div id="room_booking_table">
        <h2>Room Booking Details</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Room</th>
                    <th>Start</th>
                    <th>Finish</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr id="book_id_<?=$book['id']?>">
                        <td><?=$book['user'] ?></td>
                        <td><?=$book['room'] ?></td>
                        <td><?=date('d-m-Y H:i:s', strtotime($book['book_start'])) ?></td>
                        <td><?=date('d-m-Y H:i:s', strtotime($book['book_finish'])) ?></td>
                        <td><button type="button" class="btn btn-danger btn-xs" onclick="deleteBook(<?=$book['id']?>); return false;">Delete</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('#allusers').autocomplete({
            source: "<?php echo site_url('book/getKeyusers'); ?>",
            select: function(event, ui) {
                $('#user_id').val(ui.item.id);
                console.log(ui.item.id);

                var user_id = ui.item.id;

                $('#rooms').autocomplete({
                    source: "<?php echo site_url('book/getKeywordUserRooms'); ?>" + "/" + user_id,
                    select: function(revent, rui) {
                        $('#room_id').val(rui.item.id);
                        console.log(rui.item.id);
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
        var roomNum = $('#room_id').val();
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
        var form_data = $('#addnewbookform').serialize();
        $('#userFormDiv').removeClass('has-error');
        $('#userFormDiv span.required').empty();

        $('#roomFormDiv').removeClass('has-error');
        $('#roomFormDiv span.required').empty();

        $('#booktimeFormDiv').removeClass('has-error');
        $('#booktimeFormDiv span.required').empty();

        $.ajax({
            url: "<?php echo site_url('book/addBook'); ?>",
            data: form_data,
            dataType: "json",
            type: "POST",
            success: function(data) {
                console.log(data);
                if (data.success) {
                    $('#room_booking_table table tbody').append('<tr id="book_id_'+data.id+'"><td>'+data.user+'</td><td>'+data.room+'</td><td>'+data.book_start+'</td><td>'+data.book_finish+'</td><td><button type="button" class="btn btn-danger btn-xs" onclick="deleteBook('+data.id+'); return false;">Delete</button></td></tr>');
                    $('#rooms').val('');
                    $('#room_id').val('');
                    $('#allusers').val('');
                    $('#user_id').val('');

                    $('#validation_message').html('<h3>Room booked successful</h3>');
                } else if(data.timewrong) {
                    $('#validation_message').html('<h3>Faild to book a room. The room has been booked and check the room time please</h3>');
                    $('#booktimeFormDiv').addClass('has-error').append('<span class="required">This Room has been booked in your time</span>');
                } else {
                    $('#validation_message').html('<h3>Faild to book a room</h3>');
                    if ($('#rooms').val() == '') {
                        $('#roomFormDiv').addClass('has-error').append('<span class="required">* Field Required</span>');
                    }
                    if ($('#allusers').val() == '') {
                        $('#userFormDiv').addClass('has-error').append('<span class="required">* Field Required</span>');
                    }
                }
            }
        });
        return false;
    }

    var deleteBook = function(book_id) {
        if (confirm('Are you sure want to delet the booking?')) {
            $.ajax({
                url: "<?php echo site_url('book/removeBook'); ?>",
                data: {'book_id': book_id},
                dataType: "json",
                type: "POST",
                success: function(data) {
                    if (data.success) {
                        $('#validation_message').html('<h3>A Room booking has been removed</h3>');
                        $('#book_id_'+book_id).remove();
                    } else {
                        $('#validation_message').html('<h3>Cannot remove a room book</h3>');
                    }
                }
            });
        }
        return false;
    }
</script>