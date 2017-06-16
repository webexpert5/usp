
function rmAutocomplete(curr_id) {

    this.componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };
    var self = this;


    this.rmInitAutocomplete = function () {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        this.autocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById(curr_id)),
                {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        this.autocomplete.addListener('place_changed', this.fillInAddress);
    };

    this.fillInAddress = function () {
        // Get the place details from the autocomplete object.
        var place = self.autocomplete.getPlace();

        for (var component in self.componentForm) {
            document.getElementById(curr_id + '_' + component).value = '';
            document.getElementById(curr_id + '_' + component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (self.componentForm[addressType]) {
                var val = place.address_components[i][self.componentForm[addressType]];
                document.getElementById(curr_id + '_' + addressType).value = val;
            }
        }
    };

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
    this.geolocate = function () {

        if (this.autocomplete === undefined)
            this.rmInitAutocomplete();
        //console.log(this);

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                self.autocomplete.setBounds(circle.getBounds());
            });
        }
    };

    this.callback = function (position) {
        var geolocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };
        var circle = new google.maps.Circle({
            center: geolocation,
            radius: position.coords.accuracy
        });
        this.autocomplete.setBounds(circle.getBounds());
    };

}

// To prevent submission of the form when 'enter' is pressed in autocomplete textobox
if (typeof rm_prevent_submission !== 'function') {
    function rm_prevent_submission(event) {
        if (event.which == 13 || event.keyCode == 13) {
            event.preventDefault();
        }
    }
}