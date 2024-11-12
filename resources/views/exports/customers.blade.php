<html>
<body>
<table class="table table-striped">
    <thead>
    <tr>
    <tr>
        <th width="150%">{{__('ID')}}</th>
        <th width="150%">{{__('Name')}}</th>
        <th width="150%">{{__('Email')}}</th>
        <th width="150%">{{__('Phone')}}</th>
        <th width="150%">{{__('Default address')}}</th>
        <th width="150%">{{__('Gender')}}</th>
        <th width="150%">{{__('Orders Count')}}</th>
        <th width="150%">{{__('Addresses Count')}}</th>

    </tr>
    </thead>
    <tbody>

    @foreach($customers as $val)
        <tr>
            <td >
                {{$val->id}}
            </td>
            <td >
                {{$val->name}}
            </td>
            <td >
                {{$val->email}}
            </td>
            <td >
                {{$val->phone}}
            </td>
            <td >
                {{$val->address?->street .' - '. $val->address?->city . ' - '.$val->address?->country}}
            </td>
            <td >
                {{$val->gender}}
            </td>
            <td >
                {{$val->orders_count}}
            </td>
            <td >
                {{$val->addresses_count}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
