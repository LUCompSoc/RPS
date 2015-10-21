module.exports = {
	play: function(token, move)
	{
		this.justdoit(
		{
			'move' : move
		,	'token' : token
		});
	}

,	test: function(move)
	{
		this.justdoit(
		{
			'move' : move
		,	'token' : null
		});
	}

	// private method
,	justdoit: function(data)
	{
		console.log('Sending: ' + data.move);

		var request = require('request');

		request.post
		(
			{
				url		: 'http://10.32.172.132/play'
			,	form	: data
			}
		,	function(err,httpResponse,body)
			{
				console.log('Received: ' + body);
			}
		);
	}
};