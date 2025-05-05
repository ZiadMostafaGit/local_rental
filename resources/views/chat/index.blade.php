<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<div class="chat-container">
    <div class="chat-header">
        <h4>محادثة بين العميل والمالك على المنتج: {{ $conversation->item->name }}</h4>
        <p>العميل: {{ $conversation->customer->first_name }} {{ $conversation->customer->last_name }} | المالك: {{ $conversation->lender->first_name }} {{ $conversation->lender->last_name }}</p>
    </div>
    
    <div class="chat-body" id="messages">
        @foreach ($conversation->messages as $message)
            <div class="message">
                <strong>{{ $message->sender == 'customer' ? $conversation->customer->first_name : $conversation->lender->first_name }}:</strong>
                <p>{{ $message->message_content }}</p>
            </div>
        @endforeach
    </div>

    <form id="message-form">
        <textarea name="message_content" id="message_content" rows="4" placeholder="اكتب رسالتك..."></textarea>
        <button type="submit">إرسال</button>
    </form>
</div>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<!-- JavaScript with constants -->

<script>
    Pusher.logToConsole = true;
    var customerId = {{ $currentCustomerId ?? 'null' }};
    var lenderId = {{ $currentLenderId ?? 'null' }};
    var conversationId = {{ $conversation->id }};
 
     // تأكد من أنك تمرر المعرف بشكل صحيح
    var pusher = new Pusher('18d793925f8c0694eafe', {
        cluster: 'mt1'
    });

    var channel = pusher.subscribe('conversation.' + conversationId);

    channel.bind('message.sent', function(data) {
        var message = data.message;
        var messageElement = document.createElement('div');
        messageElement.innerHTML = '<strong>' + (message.sender === 'customer' ? 'العميل' : 'المالك') + ':</strong> ' + message.message_content;
        document.getElementById('messages').appendChild(messageElement);
    });

    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();

        var messageContent = document.getElementById('message_content').value;

        fetch(`/conversations/${conversationId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                message_content: messageContent
            })
        })
        .then(response => response.json())
        .then(data => {
            var messageElement = document.createElement('div');
            messageElement.innerHTML = '<strong>' + (data.sender === 'customer' ? 'العميل' : 'المالك') + ':</strong> ' + data.message_content;
            document.getElementById('messages').appendChild(messageElement);
            document.getElementById('message_content').value = '';
        });
    });
</script>
