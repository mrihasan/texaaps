<div class="tab-pane" id="invoice">
    <h5>{{($user->user_type_id==3)?'Sales & Return':'Purchase & Putback'}}
        History
        of {{$user->name}}</h5>
    <div class="table">
        <table class="table  table-striped table-bordered table-hover tab_1_table">
            <thead>
            <tr>
                <th>{{ __('all_settings.Transaction') }} <br/>Code</th>
                <th>{{ __('all_settings.Transaction') }} <br/>Date</th>
                <th>{{ __('all_settings.Transaction') }} <br/>Type</th>
                <th>Product <br/>Total</th>
                <th>Vat<br/>(%)</th>
                <th>Disc<br/>(%)</th>
                <th>Net <br/>Amount</th>
                <th>Less<br/> Amount</th>
                <th>Invoice<br/> Total</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right"></td>
                <td></td>
                <td></td>
                <td style="text-align: right"></td>
                <td style="text-align: right"></td>
                <td style="text-align: right"></td>
            </tr>
            </tfoot>
            @foreach($user->invoices as $stu)
                <tr>
                    <td>
                        <a href="{{ url('invoice/' . $stu->id ) }}" class="btn btn-success btn-xs"
                           title="Show"><span class="far fa-eye" aria-hidden="true"></span></a>
                        {{$stu->transaction_code}}</td>
                    <td>{{Carbon\Carbon::parse($stu->transaction_date)->format('d-M-Y')}}</td>
                    <td>{{$stu->transaction_type}}</td>
                    <td style="text-align: right">{{$stu->product_total}}</td>
                    <td>{{$stu->vat.' ('.$stu->vat_per.')'}}</td>
                    <td>{{$stu->discount.' ('.$stu->disc_per.')'}}</td>
                    <td style="text-align: right">{{$stu->total_amount}}</td>
                    <td style="text-align: right">{{$stu->less_amount}}</td>
                    <td style="text-align: right">{{$stu->invoice_total}}</td>

                </tr>
            @endforeach
        </table>
    </div>

</div>