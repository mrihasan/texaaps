@extends('layouts.al305_main')
{{--@section('expense_mo','menu-open')--}}
{{--@section('expense','active')--}}
{{--@section(($expense->approved_by != null)?'manage_expense_approved':'manage_expense','active')--}}
{{--@section('title','Manage Expense')--}}
{{--@section('breadcrumb')--}}
    {{--<li class="nav-item d-none d-sm-inline-block">--}}
        {{--<a href="{{ url('expense') }}" class="nav-link">Expense</a>--}}
    {{--</li>--}}
    {{--<li class="nav-item d-none d-sm-inline-block">--}}
        {{--<a href="#" class="nav-link">Show Expense</a>--}}
    {{--</li>--}}
{{--@endsection--}}
@section($sidebar['main_menu'].'_mo','menu-open')
@section($sidebar['main_menu'],'active')
@section('manage_'.$sidebar['module_name_menu'],'active')
@section('title','Manage '.$sidebar['module_name'])
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">{{$sidebar['main_menu_cap']}}</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">{{'Manage '.$sidebar['module_name']}}</a>
    </li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/sweetalert/sweetalert2.css') }}">

@endpush
@section('maincontent')

    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">{{$sidebar['module_name']}} Details</a>
                </li>
            </ul>
        </div>
        <div class="card-body">

            <div class="tab-content">
                <div class="active tab-pane" id="custom-tabs-one-home">
                    <table class="table table-bordered table-striped" id="print_this0">
                        <tbody>
                        <tr style="border: none">
                            <td style="border: none" colspan="3"><img
                                        src="{!! asset( 'storage/images/pad_top.png'. '?'. 'time='. time()) !!}"
                                        width="25%"
                                        class="" style="border: none"></td>
                        </tr>
                        <tr style="border: none">
                            <td style="border: none" colspan="3">
                                <h3 style="text-align: center">{{$sidebar['module_name']}}
                                </h3>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                ID
                            </th>
                            <td colspan="2">
                                {{ $expense->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Serial No
                            </th>
                            <td colspan="2">
                                {{ $expense->sl_no??'' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Tracking ID
                            </th>
                            <td colspan="2">
                                {{ $expense->transaction_code }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Date
                            </th>
                            <td colspan="2">
                                {{ Carbon\Carbon::parse($expense->expense_date)->format('d-M-Y') }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{$sidebar['module_name']}} Type
                            </th>
                            <td colspan="2">
                                {{ $expense->expense_type->expense_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Amount
                            </th>
                            <td colspan="2">
                                {{ number_format($expense->expense_amount,0) }}
                            </td>
                        </tr>
                        @if($sidebar['module_name']=='Fixed Asset')
                        <tr>
                            <th>
                                Deprecation (%)
                            </th>
                            <td colspan="2">
                                {{ number_format($expense->deprecation,0) }}
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th>
                                Transaction Made From
                            </th>
                            <td colspan="2">
                                {{ transactionMadeFromAccount($expense->transaction_code)->bank_account->account_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Transaction Method
                            </th>
                            <td colspan="2">
                                {{ $expense->transaction_method->title }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Branch
                            </th>
                            <td colspan="2">
                                {{ $expense->branch->title }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Comments
                            </th>
                            <td colspan="2">
                                {{ $expense->comments }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Status
                            </th>
                            <td colspan="2">
                                {{ $expense->status }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Submitted By
                            </th>
                            <td colspan="2">
                                {{ $expense->user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Submitted Date
                            </th>
                            <td colspan="2">
                                {{ Carbon\Carbon::parse($expense->created_at)->format('d-M-Y h:iA') }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Last Updated By
                            </th>
                            <td colspan="2">
                                {{ $expense->updatedBy->name??'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Last Updated Date
                            </th>
                            <td colspan="2">
                                {{ ($expense->updated_by)?Carbon\Carbon::parse($expense->updated_at)->format('d-M-Y h:iA'):'N/A' }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Checked By
                            </th>
                            <td colspan="2">
                                @if( $expense->checked_by == null && Auth::user()->hasRole('Checked'))
                                    <button class="btn btn-warning btn-xs" title="Verify" type="button"
                                            onclick="checkedPost({{$expense->id}})">
                                        <i class="fa fa-check-circle"></i>
                                        <span>Check please</span>
                                    </button>
                                    <form method="post" action="{{route('checked_expense', $expense->id)}}"
                                          id="check-form{{$expense->id}}" style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                @elseif( $expense->checked_by == null)
                                    <span class="right badge badge-danger">Not Yet Checked</span>
                                @else
                                    {{$expense->checkedBy->name??''}}
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <th>
                                Checked Date
                            </th>
                            <td colspan="2">
                                {{ ($expense->checked_date)?Carbon\Carbon::parse($expense->checked_date)->format('d-M-Y h:i:s'):'Not Yet checked' }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Approved By
                            </th>
                            <td colspan="2">
                                @if($expense->checked_by == null)
                                    <span class="right badge badge-danger">Not Yet Verified</span>
                                @else

                                @if( $expense->approved_by == null && Auth::user()->hasRole('Approval'))
                                    <button class="btn btn-success btn-xs" title="Approve" type="button"
                                            onclick="approvePost({{$expense->id}})">
                                        <i class="fa fa-check-circle"></i>
                                        <span>Approve Please</span>
                                    </button>
                                    <form method="post" action="{{route('approve_expense', $expense->id)}}"
                                          id="approval-form{{$expense->id}}" style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                @elseif( $expense->approved_by == null)
                                    <span class="right badge badge-danger">Not Yet Approved</span>
                                @else
                                    {{$expense->approvedBy->name}}
                                @endif
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left; border: none" width="45%">
                                Approved Date
                            </th>
                            <td style="text-align:left; border: none" width="55%" colspan="2">
                                {{ ($expense->approved_date)?Carbon\Carbon::parse($expense->approved_date)->format('d-M-Y h:i:s'):'Not Yet Approved' }}
                            </td>
                        </tr>
                        <tr style="border: none">
                            <td style="text-align:left; border: none" width="35%">
                                Prepared by<br/><br/>
                                @if($expense->user->employee && ($expense->user->imageprofile->sign!='default_sign'||$expense->user->imageprofile->sign!=null))
                                    <img src="{!! asset( 'storage/sign/'. $expense->user->imageprofile->sign. '?'. 'time='. time()) !!}"
                                         class="img-fluid" alt="Sign Image">
                                @else
                                    <img src="{!! asset( 'storage/sign/blank_sign.png'. '?'. 'time='. time()) !!}"
                                         class="img-fluid" alt="Sign Image">
                                @endif

                                <address>
                                    ______________________<br/>
                                    {{$expense->user->name}}<br/>
                                    {{($expense->user->employee)?$expense->user->employee->designation:'N/A'}}
                                    <br/>
                                    {{setting_info()['org_name']}}
                                </address>
                            </td>

                            <td style="text-align:center; border: none" width="30%">
                                Checked by<br/><br/>
                                @if($expense->user->employee && $expense->checked_by!=null && ($expense->checkedBy->employee->user->imageprofile->sign!='default_sign'||$expense->checkedBy->employee->user->imageprofile->sign!=null))
                                    <img src="{!! asset( 'storage/sign/'. $expense->checkedBy->employee->user->imageprofile->sign. '?'. 'time='. time()) !!}"
                                         class="img-fluid" alt="Sign Image">
                                @else
                                    <img src="{!! asset( 'storage/sign/blank_sign.png'. '?'. 'time='. time()) !!}"
                                         class="img-fluid" alt="Sign Image">
                                @endif
                                <address>
                                    ______________________<br/>
                                    {{($expense->checked_by)?$expense->checkedBy->name:'Not Yet Checked'}}
                                    <br/>
                                    {{($expense->checked_by && $expense->checkedBy->employee)?$expense->checkedBy->employee->designation:'N/A'}}
                                    <br/>
                                    {{($expense->checked_by)?setting_info()['org_name']:''}}
                                </address>
                            </td>
                            <td style="text-align:right; border: none" width="35%">
                                Approved By<br/><br/>
                                @if($expense->user->employee && $expense->approved_by!=null && ($expense->approvedBy->employee->user->imageprofile->sign!='default_sign'||$expense->approvedBy->employee->user->imageprofile->sign!=null))
                                    <img src="{!! asset( 'storage/sign/'. $expense->approvedBy->employee->user->imageprofile->sign. '?'. 'time='. time()) !!}"
                                         class="img-fluid" alt="Sign Image">
                                @else
                                    <img src="{!! asset( 'storage/sign/blank_sign.png'. '?'. 'time='. time()) !!}"
                                         class="img-fluid" alt="Sign Image">
                                @endif

                                <address>
                                    ______________________<br/>
                                    {{($expense->approved_by)?$expense->approvedBy->name:'Not Yet Approved'}}
                                    <br/>
                                    {{($expense->approved_by && $expense->approvedBy->employee)?$expense->approvedBy->employee->designation:'N/A'}}
                                    <br/>
                                    {{($expense->approved_by)?setting_info()['org_name']:''}}
                                </address>
                            </td>

                        </tr>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                        class="fa fa-arrow-left"
                        aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
            <a type="button" id="pbutton0" class="btn btn-warning pull-right"><i
                        class="fa fa-print"> Print</i></a>
            @can('ExpenseDelete')
                {!! Form::open([
        'method'=>'DELETE',
        'url' => ['expense', $expense->id],
        'style' => 'display:inline'
    ]) !!}
                {!! Form::button('<span class="far fa-trash-alt" aria-hidden="true" title="Delete" />', array(
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-xs fa-pull-right',
                        'title' => 'Delete',
                        'onclick'=>'return confirm("Confirm delete?")'
                ))!!}
                {!! Form::close() !!}
            @endcan
            @can('ExpenseAccess')
                <a href="{{ url('expense/' . $expense->id . '/edit') }}"
                   class="btn btn-info btn-xs fa-pull-right" title="Edit" style="margin-right: 10px"><span
                            class="far fa-edit"
                            aria-hidden="true"></span></a>
            @endcan
        </div>
    </div>
@endsection
@push('js')
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('supporting/sweetalert/sweetalert2.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $('.dataTables').DataTable({
            aaSorting: [],
            lengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"] // change per page values here
            ],
            pageLength: 25,
            responsive: true,
            fixedHeader: true,
//            dom: '<"html5buttons"B>lTfgtip',
            'dom': "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            columnDefs: [
//                { targets: [ 0,1,2,3,4, 5, 6, 7, 8, 9 ], className: 'dt-head text-center'  },
//                { targets: [0,1,2,3,4, 5,6,7 ], className: 'text-center' },
                {targets: [0], className: 'text-center'},
//                {targets: [4], className: 'text-right'},
            ],

            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {
                    extend: 'excel', title: '{{ config('app.name', 'EIS') }}',
                    messageTop: ' Product Type   '
                },
                    {{--{extend: 'pdf', title: 'DVL Transaction Data',--}}
                    {{--messageTop: 'Commission Report of {{entryBy($partner_id).' '. $title_date_range}} ',--}}
                    {{--messageBottom: '{{\Carbon\Carbon::now()->format(' D, d-M-Y, h:ia')}}'--}}
                    {{--},--}}

                {
                    extend: 'pdfHtml5',

                    className: 'btn  btn-sm btn-table',
                    titleAttr: 'Export to Pdf',
                    text: '<span class="fa fa-file-pdf-o fa-lg"></span><i class="hidden-xs hidden-sm hidden-md"> Pdf</i>',
                    filename: 'Product Type ',
                    extension: '.pdf',
//                    orientation : 'landscape',
                    orientation: 'portrait',
                    title: "Product Type ",
                    footer: true,
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)',
                        orthogonal: "Export-pdf"
                    },
                    customize: function (doc) {
                        var rowCount = doc.content[1].table.body.length;
                        for (i = 1; i < rowCount; i++) {

                            /*var val = document.form1.campo.value;
                             if (isNaN(val)){
                             alert(‘Il valore inserito non è numerico’);
                             } else {
                             alert(‘Il valore inserito è numerico’);
                             }*/
                            doc.content[1].table.body[i][0].alignment = 'center';
                            doc.content[1].table.body[i][1].alignment = 'left';
                            doc.content[1].table.body[i][2].alignment = 'left';
//                            doc.content[1].table.body[i][3].alignment = 'left';
//                            doc.content[1].table.body[i][4].alignment = 'right';
//                            doc.content[1].table.body[i][5].alignment = 'right';
//                            doc.content[1].table.body[i][6].alignment = 'right';
//                            doc.content[1].table.body[i][7].alignment = 'left';
//                            doc.content[1].table.body[i][8].alignment = 'left';
                        }
                        doc.content[1].table.widths = ['10%', '50%', '40%'];
//                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.content.splice(0, 1);
                        var now = new Date();
                        var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear() + ' ' + now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
                        var logo = '';
                        {{--var header_title = '';--}}
                            doc.pageMargins = [10, 50, 10, 40];
                        doc.defaultStyle.fontSize = 7;

                        doc.defaultStyle.alignment = 'left';
                        doc.styles.tableHeader.alignment = 'left';
                        doc.styles.tableHeader.fontSize = 10;
                        doc.styles.tableFooter.fontSize = 10;
                        doc['header'] = (function () {
                            return {
                                columns: [
                                    {
                                        alignment: 'left',
                                        italics: true,
                                        text: 'Product Type ',
                                        fontSize: 10,
                                        margin: [10, 0]
                                    },
                                    {
                                        //image: logo,
                                        alignment: 'center',
                                        width: 20,
                                        height: 20,
                                        {{--image: 'data:image/png;base64,{{$settings->logo_base64}}'--}}

                                    },

                                    {
                                        alignment: 'right',
                                        fontSize: 10,
                                        text: '{{ config('app.name', 'EIS') }}'
                                    }
                                ],
                                margin: 20
                            };
                        });
                        doc['footer'] = (function (page, pages) {
                            return {
                                columns: [
                                    {
                                        alignment: 'left',
                                        text: ['Print On: ', {text: jsDate.toString()}]
                                    },

                                    {
                                        alignment: 'right',
                                        text: ['Pages ', {text: page.toString()}, ' of ', {text: pages.toString()}]
                                    }
                                ],
                                margin: 20
                            };
                        });
                        var objLayout = {};
                        objLayout['hLineWidth'] = function (i) {
                            return .5;
                        };
                        objLayout['vLineWidth'] = function (i) {
                            return .5;
                        };
                        objLayout['hLineColor'] = function (i) {
                            return '#aaa';
                        };
                        objLayout['vLineColor'] = function (i) {
                            return '#aaa';
                        };
                        objLayout['paddingLeft'] = function (i) {
                            return 4;
                        };
                        objLayout['paddingRight'] = function (i) {
                            return 4;
                        };
                        doc.content[0].layout = objLayout;
                    }
                },
                {
                    extend: 'print',
                    footer: true,
                    messageTop: 'Product Type  ',
                    messageBottom: '{{'Printed On: '.\Carbon\Carbon::now()->format(' D, d-M-Y, h:ia')}}',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]

        });
    });

</script>
<script type="text/javascript">

    function checkedPost(id) {
//        console.log(id);
        var id = id;
        const swalWithBootstrapButtons = swal.mixin({
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        })

        swalWithBootstrapButtons({
            title: 'Are you sure?',
            text: "You want to verify this Request!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Verify it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.value
    )
        {
            document.getElementById('check-form' + id).submit();
            event.preventDefault();
        }
    else
        if (
            // Read more about handling dismissals
        result.dismiss === swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons(
                'Cancelled',
                'The user remain pending :)',
                'info'
            )
        }
    })
    }
</script>

<script type="text/javascript">

    function approvePost(id) {
        console.log(id);
        var id = id;
        const swalWithBootstrapButtons = swal.mixin({
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        })

        swalWithBootstrapButtons({
            title: 'Are you sure?',
            text: "You want to approve this Expense!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.value
    )
        {
            document.getElementById('approval-form' + id).submit();
            event.preventDefault();
        }
    else
        if (
            // Read more about handling dismissals
        result.dismiss === swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons(
                'Cancelled',
                'The user remain pending :)',
                'info'
            )
        }
    })

    }

</script>
<script src="{!! asset('supporting/printthis.js') !!}" type="text/javascript"></script>
<script type="text/javascript">
    $('#pbutton0').on('click', function () {
        $("#print_this0").printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            printContainer: true,
//            loadCSS: "../../../public/tf/global/plugins/bootstrap/css/bootstrap.min.css",
            pageTitle: "",
            removeInline: false,
            printDelay: 333,
            header: null,
            footer: null,
            base: false,
//            formValues: true,
            canvas: false,
//            doctypeString: "...",
            removeScripts: false,
            copyTagClasses: false
        });
    });
</script>


@endpush
