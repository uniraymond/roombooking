<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container">
    <div class="title"><h1><?=$title ?></h1></div>
    <?php echo validation_errors(); ?>
    <div>
        <?php echo form_open('book/booking'); ?>
        <div class="form-group">
            <label for="user">Select a user</label>
            <?php echo form_dropdown('user', $users, 0, 'class="form-control"'); ?>
        </div>
        <div class="form-group">
            <label for="room">Select a room</label>
            <?php echo form_dropdown('room', $rooms, 0, 'class="form-control"'); ?>
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
                <input type="text" class="form-control input-small" id="booktime" placeholder="">
                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
            </div>
        </div>
        <div class="form-group">
            <label for="bookuni">Booking Hours</label>
            <input type="number" class="form-control" id="bookuni" placeholder="Hours" max="10" min="1">
        </div>
        <button type="reset" class="btn btn-default">Reset</button>
        <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
</div>
<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
<script type="text/javascript">
</script>