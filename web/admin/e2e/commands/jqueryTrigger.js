exports.command = function(selector, events, callback) {
    let client = this;

    this.execute(trigger, [selector, events], execCallback);

    function trigger(selector, events) {
        let element = $(selector);
        if( typeof events === 'string' ) {
            events = [events];
        }
        for (let idx in events) {
            element.trigger(events[idx]);
        }

        return {'match': element.length > 0, 'selector': selector};
    }

    function execCallback(result) {
        if (typeof callback === 'function') {
            callback.call(client, result.value);
        }
    }

    return this;
};
