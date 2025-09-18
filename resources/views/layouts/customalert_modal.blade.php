<div class="container">
    <div class="modal fade" id="customAlertModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $ApplicationSetting->item_name }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <p id="customAlertMessage" class="my-0 font-weight-bold"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
</div>
