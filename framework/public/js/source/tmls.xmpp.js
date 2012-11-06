var bare_cookie = getCookie('tmls_jsess').split('-');
var Chat = {
    connection : null,
    service: null,
    node: null,
    CHAT_ROSTER : '#tmls-chat-roster',
    BOSH_SERVICE : 'http://www.tenantmls.com/xmpp-httpbind',
    NS_DATA_FORMS: "jabber:x:data",
    NS_PUBSUB: "http://jabber.org/protocol/pubsub",
    NS_PUBSUB_OWNER: "http://jabber.org/protocol/pubsub#owner",
    NS_PUBSUB_ERRORS: "http://jabber.org/protocol/pubsub#errors",
    NS_PUBSUB_NODE_CONFIG: "http://jabber.org/protocol/pubsub#node_config",
    // We can encryt this into a cookie and pull from it
    jid : bare_cookie[0]+'/TenantMLS-Web',
    password : bare_cookie[1],

    jid_to_id : function(jid) {
        return Strophe.getBareJidFromJid(jid).replace("@",'-').replace(".",'-').replace(".",'-');
    },
    fetch_vcard : function(iq) {
    },

    on_roster : function(iq) {
        $(iq).find('item').each(function() {
            var jid = $(this).attr('jid');
            var name = $(this).attr('name') || jid;
            var sub = $(this).attr('subscription') || 0;
            var group = $(this).find('group').text();

            // transform jid into an id
            var jid_id = Chat.jid_to_id(jid);
            var contact_class_hidden = '';
            if (sub === 'to' || sub === 'from')
                contact_class_hidden = ' class="hidden"';

            var contact = $('<li id="'+jid_id+'"'+contact_class_hidden+'>' +
                            '<div class="roster-contact offline">' +
                            '<div class="roster-group">'+group+'</div>' + 
                            '<div class="status">&nbsp;</div>' + 
                            '<div class="roster-name">' + 
                            name +
                            '</div><div class="roster-jid">' + jid + 
                            '</div></div></li>');
            if (sub === 'to') {
            } else if (sub === 'from') {
                // Add a notification to the sidebar
                if ($('#manage-notification').length===0)
                    $('span#manage').parent('li').addClass('hover').append('<span class="notification" id="manage-notification">0</span>');
                var notification = $('#manage-notification').text();
                var number = parseInt(notification);
                $('#manage-notification').text(number + 1);
            }
            Chat.insert_contact(contact);
        });
    },

    pending_subscriber : null,

    on_presence : function(presence) {
        var ptype = $(presence).attr('type');
        var from = $(presence).attr('from');
        var name = $(presence).attr('name');
        var jid_id = Chat.jid_to_id(from);

        if (ptype === 'subscribe') {
            // Check if the "from" user is already in our roster
            // If not, add a notification to the sidebar - make a noise
            // Otherwise, auto accept the subscription request and make the subcribe both
            if ($(Chat.CHAT_ROSTER+' ul li#'+jid_id).length===0) {
                Chat.connection.send($pres({to: from,"type": "subscribed"}));
            }
            if ($('#manage-notification').length===0)
                $('span#manage').parent('li').addClass('hover').append('<span class="notification" id="manage-notification">0</span>');
           // Play a sound
           if ($('#notify').length===0)
                $('body').append("<embed src='"+TenantMLS.BASE_PATH+"/src/notify.mp3' hidden=true autostart=true loop=false id='notify'>");
            else {
                $('#notify').remove();
                $('body').append("<embed src='"+TenantMLS.BASE_PATH+"/src/notify.mp3' hidden=true autostart=true loop=false id='notify'>");
            }
            var notification = $('#manage-notification').text();
            var number = parseInt(notification);
            $('#manage-notification').text(number + 1);
        } else if (ptype !== 'error') {
            var contact = $(Chat.CHAT_ROSTER+' ul li#' + jid_id + ' .roster-contact')
                .removeClass("online")
                .removeClass("away")
                .removeClass("offline");
            if (ptype === 'unavailable') {
                contact.addClass("offline");
            } else {
                var show = $(presence).find("show").text();
                if (show === "" || show === "chat") {
                    contact.addClass("online");
                } else {
                    contact.addClass("away");
                }
            }

            var li = contact.parent();
            li.remove();
            Chat.insert_contact(li);
        }

        // reset addressing for user since their presence changed
        var jid_id = Chat.jid_to_id(from);
        $('#chat-'+jid_id).data('jid',Strophe.getBareJidFromJid(from));

        return true;
    },

    on_roster_changed: function (iq) {
        $(iq).find('item').each(function () {
            var sub = $(this).attr('subscription');
            var jid = $(this).attr('jid');
            var name = $(this).attr('name') || jid;
            var jid_id = Chat.jid_to_id(jid);

            if (sub === 'remove') {
                // contact is being removed
                $('#' + jid_id).remove();
            } else if (sub === 'from') {
                 // Add a notification to the sidebar
                if ($('#manage-notification').length===0)
                    $('span#manage').parent('li').addClass('hover').append('<span class="notification" id="manage-notification">0</span>');
                // Play a sound
               if ($('#notify').length===0)
                    $('body').append("<embed src='"+TenantMLS.BASE_PATH+"/src/notify.mp3' hidden=true autostart=true loop=false id='notify'>");
                else {
                    $('#notify').remove();
                    $('body').append("<embed src='"+TenantMLS.BASE_PATH+"/src/notify.mp3' hidden=true autostart=true loop=false id='notify'>");
                }
                var notification = $('#manage-notification').text();
                var number = parseInt(notification);
                $('#manage-notification').text(number + 1);
            } else {
                // contact is being added or modified
                var contact_html = '<li id="' + jid_id + '">' +
                    '<div class="' + 
                    ($('#' + jid_id).attr('class') || 'roster-contact offline') +
                    '">' +
                    '<div class="status">&nbsp;</div>' + 
                    '<div class="roster-name">' +
                    name +
                    '</div><div class="roster-jid">' +
                    jid_id +
                    '</div></div></li>';

                if ($('#' + jid_id).length > 0) {
                    $('#' + jid_id).replaceWith(contact_html);
                } else {
                    Chat.insert_contact(contact_html);
                }
            }
        });

        return true;
    },
    get_requests: function(iq) {

    },
    presence_value: function(elem) {
        if (elem.hasClass('online')) {
            return 2;
        } else if (elem.hasClass('away')) {
            return 1;
        }
        return 0;
    },

    insert_contact: function (elem) {
        var jid = $(elem).find('.roster-jid').text();
        var pres = $(elem).find('.roster-contact');
        var group = $(elem).find('.roster-group').text();
        var contacts = $(Chat.CHAT_ROSTER+' ul#group-'+group+' li');

        if (contacts.length > 0) {
            var inserted = false;
            contacts.each(function () {
                var cmp_pres = Chat.presence_value(
                    $(this).find('.roster-contact'));
                var cmp_jid = $(this).find('.roster-jid').text();

                if (pres > cmp_pres) {
                    $(this).before(elem);
                    inserted = true;
                    return false;
                } else {
                    if (jid < cmp_jid) {
                        $(this).before(elem);
                        inserted = true;
                        return false;
                    }
                }
            });

            if (!inserted) {
                $(Chat.CHAT_ROSTER+' ul#group-'+group).append(elem);
            }
        } else {
            $(Chat.CHAT_ROSTER+' ul#group-'+group).append(elem);
        }
    },
    on_message: function(message) {
        var full_jid = $(message).attr('from');
        var jid = Strophe.getBareJidFromJid(full_jid);
        var jid_id = Chat.jid_to_id(jid);
        var nick = $(message).find('nick').text();
        var name = nick;
        var composing = $(message).find('composing');

        /* Disabled
         * Reason: Chat pops up before user types their message with "xxx is typing..."; looks stupid
         */
        //$('#chat-' + jid_id).show();
        //$('#chat-' + jid_id + ' input').focus();
        var body = $(message).find("html > body");

        if (body.length === 0) {
            body = $(message).find('body');
            if (body.length > 0) {
                body = body.text()
            } else {
                body = null;
            }
        } else {
            body = body.contents();

            var span = $("<span></span>");
            body.each(function () {
                if (document.importNode) {
                    $(document.importNode(this, true)).appendTo(span);
                } else {
                    // IE workaround
                    span.append(this.xml);
                }
            });

            body = span;

        }

        if (body) {
            if ($('#chat-' + jid_id).length === 0) {
            $('#tmls-chat-bar ul').append('<li id="chat-'+jid_id+'">' +
                '<div class="chat-title"><span class="chat-controls"><a href="javascript:void(0)">x</a></span>' + name + '</div>' +
                "<div class='chat-body'><div class='chat-messages'></div>" +
                '<div class="chat-inp-container">' +
                "<input type='text' class='chat-input'></div></div></li>");
            }
            
            $('#chat-' + jid_id).data('jid', full_jid);

            // remove notifications since user is now active
            $('#chat-' + jid_id).show();
            $('#chat-' + jid_id + ' .chat-event').remove();

            // add the new message
            $('#chat-' + jid_id + ' .chat-messages').append(
                "<div class='chat-message'>" +
                "<span class='chat-pic-from'>" +
                "</span><span class='chat-text chat-text-from'>" +
                "</span></div>");

            $('#chat-' + jid_id + ' .chat-message:last .chat-text')
                .append(body);

            Chat.scroll_chat(jid_id);
        }
        if (composing.length > 0 && $('#chat-' + jid_id + ' .chat-messages').length !== 0) {
            $('#chat-' + jid_id + ' .chat-messages').append(
                "<div class='chat-event'>" +
                Strophe.getNodeFromJid(jid) +
                " is typing...</div>");

            Chat.scroll_chat(jid_id);
        } else if (composing.length === 0 && $('#chat-' + jid_id + ' .chat-messages').length !== 0 && $('#chat-' + jid_id + ' .chat-event').length !== 0) {
            $('#chat-' + jid_id + ' .chat-event').remove();
        }
        $('#chat-' + jid_id + ' input').focus();
        return true;
    },
    scroll_chat: function(jid_id) {
        var div = $('#chat-'+jid_id+' .chat-messages').get(0);
        div.scrollTop = div.scrollHeight;
    }
}
$(document).ready(function () {

    $(Chat.CHAT_ROSTER).css('height',window.innerHeight*0.7);

    // Connect right away
    $(document).trigger('connect');

    $('.chat-controls a').live('click',function() {
        $(this).parent().parent().parent().remove();
    });

    $('.chat-title').live('click',function() {
        if ($(this).parent('li').hasClass('minimized'))
            $(this).parent('li').removeClass('minimized').find('.chat-spacer').remove();
        else
            $(this).parent('li').addClass('minimized').append('<div class="chat-spacer"></div>');
    });

    $('.roster-contact').live('click', function() {
        var jid = $(this).find(".roster-jid").text();
        var name = $(this).find(".roster-name").text();
        var jid_id = Chat.jid_to_id(jid);

        if ($('#chat-'+jid_id).length > 0) {
            $('#chat-'+jid_id).show();
        } else {
            $('#tmls-chat-bar ul').append('<li id="chat-'+jid_id+'">'+
                '<div class="chat-title"><span class="chat-controls"><a href="javascript:void(0)">x</a></span>' + name + '</div><div class="chat-body">' +
                '<div class="chat-messages"></div>' +
                '<div class="chat-inp-container">' +
                '<input type="text" class="chat-input"/></div></div></li>'
                );
            $('#chat-'+jid_id).data('jid',jid);
        }
        $('#chat-'+jid_id+' input').focus();
    });

    $('.chat-input').live('keypress', function (ev) {
        var jid = $(this).parent().parent().parent().data('jid');

        if (ev.which === 13) {
            ev.preventDefault();

            var body = $(this).val();
            var my_name = $('#my-chat-name').text();

            var message = $msg({to: jid,
                                "type": "chat"})
                .c('body').t(body).up()
                .c('nick', {xmlns:'http://jabber.org/protocol/nick'}).t(my_name).up()
                .c('active', {xmlns: "http://jabber.org/protocol/chatstates"});
            Chat.connection.send(message);

            $(this).parent().parent().parent().find('.chat-messages').append(
                "<div class='chat-message'>" +
                "<span class='chat-pic-to'>" +
                "</span><span class='chat-text-to'>" +
                body +
                "</span></div>");
            Chat.scroll_chat(Chat.jid_to_id(jid));

            $(this).val('');
            $(this).parent().parent().parent().data('composing', false);
        } else {

            var composing = $(this).parent().parent().parent().data('composing');

            if (!composing && $(this).val()!='') {
                var notify = $msg({to: jid, "type": "chat"})
                    .c('composing', {xmlns: "http://jabber.org/protocol/chatstates"});
                Chat.connection.send(notify);

                $(this).parent().parent().parent().data('composing', true);
            }
            if (composing && $(this).val()=='') {
                var notify = $msg({to: jid, "type": "chat"});
                $(this).parent().parent().parent().data('composing', false);
            }
        }
    });

    if ($('#tmls-chat-roster ul').children('li').length===0)
        $('#tmls-chat-roster').append('<span class="no-chat">You can add tenants or agents to your chat bar by requesting their permission</span>');
    
    $('#approve_dialog').dialog({
        autoOpen: false,
        draggable: false,
        modal: true,
        title: 'Representation Request',
        buttons: {
            "Deny": function () {
                Chat.connection.send($pres({
                    to: Chat.pending_subscriber,
                    "type": "unsubscribed"}));
                Chat.pending_subscriber = null;

                $(this).dialog('close');
            },

            "Approve": function () {
                Chat.connection.send($pres({
                    to: Chat.pending_subscriber,
                    "type": "subscribed"}));
                
                Chat.connection.send($pres({
                    to: Chat.pending_subscriber,
                    "type": "subscribe"}));
                
                Chat.pending_subscriber = null;

                $(this).dialog('close');
            }
        }
    });
});
$(document).bind('connect', function () {
    var conn = new Strophe.Connection(Chat.BOSH_SERVICE);
    // We need to figure out how to login without actually logging in and exposing the information
    conn.connect(Chat.jid, Chat.password, function(status) {
        if (status === Strophe.Status.CONNECTED) {
            $(document).trigger('connected');
        } else if (status === Strophe.Status.DISCONNECTED) {
            $(document).trigger('disconnected');
        }
    });
    Chat.connection = conn;
});
$(document).bind("connected", function() {
    //alert('connected');
    var iq = $iq({type:"get"}).c("query", {xmlns:"jabber:iq:roster"});
    Chat.connection.sendIQ(iq,Chat.on_roster);
    // set up presence handler and send initial presence
    Chat.connection.addHandler(Chat.on_presence,null,"presence");
    Chat.connection.send($pres().c('priority').t('30').c('status',null,'online'));
    // fetch our vCard
    //Chat.connection.send($iq({type:'set'}).c('vCard',{xmlns:'vcard-temp'}).c('FN').t('Thomas Lackemann'));
    //Chat.connection.send($iq({type:'get'}).c('vCard',{xmlns:'vcard-temp'}));
    Chat.connection.addHandler(Chat.on_roster_changed, "jabber:iq:roster","iq","set");
    Chat.connection.addHandler(Chat.on_message,null,"message","chat");
});
$(document).bind("disconnected", function() {
    //alert('disconnected');
});
$(document).bind("contact_approved",function(ev, data) {
    Chat.connection.send($pres({
        to: data.jid, "type": "subscribed"}));
    
    Chat.connection.send($pres({
        to: data.jid,
        "type": "subscribe"}));

    var iq = $iq({type: "set"}).c("query", {xmlns: "jabber:iq:roster"})
        .c("item", {name:data.name,jid:data.jid}).c('group').t(data.group);
        //alert(iq);
    Chat.connection.sendIQ(iq);
});
$(document).bind("contact_added", function(ev, data) {
    var iq = $iq({type: "set"}).c("query", {xmlns: "jabber:iq:roster"})
        .c("item", {name:data.name,jid:data.jid}).c('group').t(data.group);
        //alert(iq);
    Chat.connection.sendIQ(iq);
    
    var subscribe = $pres({from: data.request_jid, to: data.jid, "type": "subscribe"}).c('nick',{xmlns:'http://jabber.org/protocol/nick'}).t(data.request_name);
    //alert(subscribe);
    Chat.connection.send(subscribe);
});