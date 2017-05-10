@extends('layouts.default')

@section('content')

<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{URL::to('help') }}">Help</a> &raquo; How to update account settings</h1>

<div id="quick_start">

    <div class="guide_section help_section">

        <div class="quick_start_video vid_in_help">
            <iframe src="//player.vimeo.com/video/104409269" width="561" height="315" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>

        <h2>Text instruction</h2>

        <ol class="steps">
            <li>Open the Settings Tab from the navigation menu</li>
            <li>Click <span>System settings</span></li>
            <li>Update the settings as needed. </li>
            <li>To save your changes, click on the update button.</li>

        </ol>

        <p>The settings page is divided into page tabs to make it easy for you to set your business options. Most of the options have sensible defaults, but you can change them to fit your business requirements.</p>

        <p>In the general settings tab: </p>

            <ul>
               <li>You can set your offering such as services or products;</li>
                <li> If you provide services you can define how you bill e.g. per project or per hour.</li>
                <li> You can set your time zone, add Prefix to invoice numbers e.g. MIX000014.</li>
                <li> Change your default currency and date format;</li>
                <li> Update tax rates as well as discount and tax column display option</li>
                <li> Update footer notes</li>
            </ul>

        <p>In the notes and payment info tab</p>

        <ul>
            <li>You can change the default invoice and quote notes</li>
            <li>Update payment information for example Bank account details</li>

        </ul>

        <p>In the Invoice email tab</p>

        <ul>
            <li>You can customise the email message that Sighted use when sending invoices and quotes to your clients.  The special variables in the email will display specific invoice attributes.  For example _INVOICE_NUMBER_ will display the unique invoice ID of the invoice.</li>
            <li>Please check the meaning of the special variables or legends at the bottom of the settings page.</li>
        </ul>

    </div><!-- END Quide section -->

</div><!-- END QUICK START-->


@stop


@section('footer')

<script>

    $(function(){

        if($('#appmenu').length > 0){

            $('.more_all_menu').addClass('selected_group');
            $('.menu_help').addClass('selected');
            $('.more_all_menu ul').css({'display': 'block'});
        }

    });

</script>

@stop