<div class="tab-pane" id="ledger">
    <div class="table">
        <table class="table dataTables table-striped table-bordered table-hover " >
            <thead>
            <tr>
                <th>{{ __('all_settings.Transaction') }} <br/>Date</th>
                <th>{{ __('all_settings.Transaction') }} <br/>Type</th>
                <th>{{ __('all_settings.Transaction') }} <br/>Code</th>
                <th>Credit<br/>Amount(+)</th>
                <th>Debit<br/>Amount(-)</th>
                {{--<th>Return<br/>Amount</th>--}}
                <th>Balance</th>
                <th>Reference<br/>Comments</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td style="text-align: right"></td>
                <td style="text-align: right"></td>
                {{--<td style="text-align: right"></td>--}}
                <td></td>
                <td></td>
            </tr>
            </tfoot>
            @foreach($ledger as $key=>$data)
                <tr>
                    <td>{{Carbon\Carbon::parse($data['transaction_date'][$key])->format('d-M-Y, h:ia') }}</td>
                    <td>{{$data['transaction_type'][$key] }}</td>
                    <td>{{$data['transaction_code'][$key]}}</td>
                    {{--                                        <td>{{$data['transaction_type'][$key]}}</td>--}}
                    <td style="text-align: right">{{($data['transaction_type'][$key]=='Credited'||$data['transaction_type'][$key]=='Receipt'||$data['transaction_type'][$key]=='Purchase'||$data['transaction_type'][$key]=='Return'||$data['transaction_type'][$key]=='Payslip')?number_format($data['transaction_amount'][$key],0):''}}</td>
                    <td style="text-align: right">{{($data['transaction_type'][$key]=='Debited'||$data['transaction_type'][$key]=='Sales'||$data['transaction_type'][$key]=='Payment'||$data['transaction_type'][$key]=='Put Back')?number_format($data['transaction_amount'][$key],0):''}}</td>
{{--                    <td style="text-align: right">{{($data['transaction_type'][$key]=='Return')?$data['transaction_amount'][$key]:''}}</td>--}}
                    {{--<td>{{$data['transaction_amount'][$key]}}</td>--}}
                    <td style="text-align: right">{{number_format($data['balance'][$key],0)}}</td>
                    <td>{{$data['reference'][$key]}}</td>
                </tr>
            @endforeach
        </table>
    </div>

</div>