@if($supportTicket->priority == 'low')
    <span class="badge bg-success">
        <i class="ti ti-arrow-down ti-xs"></i>
        {{ str($supportTicket->priority)->title() }}
    </span>
@elseif($supportTicket->priority == 'medium')
    <span class="badge bg-warning">
        <i class="ti ti-arrow-right ti-xs"></i>
        {{ str($supportTicket->priority)->title() }}
    </span>
@else
    <span class="badge bg-danger">
        <i class="ti ti-arrow-up ti-xs"></i>
        {{ str($supportTicket->priority)->title() }}
    </span>
@endif
