<html>
<body>
<table class="table table-striped">
    <thead>
    <tr>
    <tr>
        <th width="150%">{{__('ID')}}</th>
        <th width="150%">{{__('user')}}</th>
        <th width="150%">{{__('subtotal amount')}}</th>
        <th width="150%">{{__('shipping')}}</th>
        <th width="150%">{{__('discount')}}</th>
        <th width="150%">{{__('total amount')}}</th>
{{--        <th width="150%">{{__('address')}}</th>--}}
        <th width="150%">{{__('Date')}}</th>
        <th width="150%">{{__('status')}}</th>

    </tr>
    </thead>
    <tbody>

    @foreach($orders as $val)
        <tr>
            <td >
                {{$val->order_id}}
            </td>
            <td >
                {{$val->user_name}}
            </td>
            <td >
                {{$val->subtotal_amount}}
            </td>
            <td >
                {{$val->shipping}}
            </td>
            <td >
                {{$val->discount}}
            </td>
            <td >
                {{$val->total_amount}}
            </td>
{{--            <td >--}}
{{--                {{$val->address}}--}}
{{--            </td>--}}
            <td >
                {{$val->order_created_at}}
            </td>
            <td >
                {{$val->order_status}}
            </td>
        </tr>
    @endforeach

    </tbody>
</table>
</body>
</html>
