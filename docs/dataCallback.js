function (data, statusText, request) {
	if (!$(this.nextSelector, data).length) {
		if (this.almostDone) {
			this.isDone = true;
			this.almostDone = false;
		} else {
			this.almostDone = true;
		}
	}
}