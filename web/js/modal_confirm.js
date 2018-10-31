var $confirmModal = jQuery("#my-confirm");

$confirmModal.find(".confirm-ok").on("click", function() {
    var action = $confirmModal.data("ok-action");
    if (action !== undefined) {
        $confirmModal.on("hidden.bs.modal", function() {
            $confirmModal.off("hidden.bs.modal", arguments.callee);
            action();
        });
    }
    $confirmModal.modal("hide");
});

yii.confirm = function(message, action) {
    $confirmModal.find(".modal-body p").text(message);
    $confirmModal.data("ok-action", action);
    $confirmModal.modal("show");
};