<div class="tab-pane" id="payment">
    <h5>Receipt/Payment
        History
        of {{$user->name}}</h5>
    <div class="table">
        <table class="table  table-striped table-bordered table-hover tab_2_table">
            <thead>
            <tr>
                <th>{{ __('all_settings.Transaction') }} <br/>Code</th>
                <th>{{ __('all_settings.Transaction') }} <br/>Date</th>
                <th>{{ __('all_settings.Transaction') }}<br/>Type</th>
                <th>{{ __('all_settings.Transaction') }}<br/>Method</th>
                <th>Comments</th>
                <th>Amount</th>
                {{--<th class="noprint">Action</th>--}}
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right"></td>
                {{--<td></td>--}}
            </tr>
            </tfoot>
            @foreach($user->ledgers as $stu)
                <tr>
                    <td>{{$stu->transaction_code}}</td>
                    <td>{{Carbon\Carbon::parse($stu->transaction_date)->format('d-M-Y')}}</td>
                    <td>{{$stu->transaction_type->title}}</td>
                    <td>{{$stu->transaction_method->title}}</td>
                    <td>{{$stu->comments}}</td>
                    <td style="text-align: right">{{number_format($stu->amount,0)}}</td>
                    {{--<td class="noprint">--}}
                        {{--<a href="{{ url('ledger/' . $stu->id ) }}" class="btn btn-success btn-xs"--}}
                           {{--title="Show"><span class="far fa-eye" aria-hidden="true"></span></a>--}}
                    {{--</td>--}}

                </tr>
            @endforeach
        </table>
    </div>

</div>