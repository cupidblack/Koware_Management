function Wo_RegisterFollow(id, is_confirm, show_modal) {
    var _follow_con = $('[id=Follow-' + id + ']');

    if (!_follow_con.length) {
        return false;
    }

    if (typeof is_confirm === 'undefined') {
        is_confirm = 0;
    }

    if (typeof show_modal === 'undefined') {
        show_modal = false;
    }

    if (show_modal === true) {
        $('#unfriend_btn').attr('onclick', 'Wo_RegisterFollow(' + id + ')');
        $('#un_friend_modal').modal('show');
        return false;
    }

    $('#un_friend_modal').modal('hide');

    var button = _follow_con.find('button').first();

    if (!button.length || button.prop('disabled') === true) {
        return false;
    }

    var previous_html = _follow_con.html();

    /*
     * Do not change the button before the server answers.
     * This prevents a failed request from leaving the UI at "Requested".
     */
    button.prop('disabled', true);
    button.attr('aria-busy', 'true');

    var request = $.ajax({
        url: Wo_Ajax_Requests_File(),
        type: 'GET',
        dataType: 'json',
        cache: false,
        data: {
            f: 'follow_user',
            following_id: id,
            hash_id: $('.main_session').first().val()
        }
    });

    request.done(function(data) {
        var status = data && parseInt(data.status, 10);
        var state = data && data.follow_state
            ? String(data.follow_state)
            : '';

        if (
            status === 200 &&
            (state === 'following' || state === 'follow')
        ) {
            /*
             * The server is authoritative. A reload obtains the actual
             * relationship state from the database.
             *
             * Keep this until a dedicated partial-button endpoint exists.
             */
            window.location.reload();
            return;
        }

        _follow_con.html(previous_html);

        var message = data && data.message
            ? String(data.message)
            : 'Unable to process follow request.';

        if (
            typeof $('#modal-alert').modal === 'function'
        ) {
            $('#modal-alert').modal('show');

            if (typeof Wo_Delay === 'function') {
                Wo_Delay(function() {
                    $('#modal-alert').modal('hide');
                }, 3000);
            }
        }

        if (typeof console !== 'undefined') {
            console.warn(
                'Follow request rejected:',
                status,
                message
            );
        }
    });

    request.fail(function(xhr) {
        _follow_con.html(previous_html);

        if (typeof console !== 'undefined') {
            console.warn(
                'Follow request failed:',
                xhr.status,
                xhr.responseText
            );
        }
    });

    request.always(function() {
        _follow_con
            .find('button')
            .prop('disabled', false)
            .removeAttr('aria-busy');
    });

    return false;
}
