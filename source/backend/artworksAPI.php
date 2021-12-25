<?php
class artworksApi {

    public $parameters;

    function getAllArtworks() {
        $artworks = artworksApi.makeUnfilteredConsult();
    }

    function getFilteredArtworks() {
        // artworksApi.makeFilteredConsult();
    }

    function init() {
        if ($_POST['filters']) {
            $parameters['filters'] = $_POST['filters'];
            // artworksApi.getFilteredArtworks();
        } else {
            // artworksApi.getAllArtworks();
        }
    }

    function makeFilteredConsult() {
        // TODO: Implement Consult to DDBB where obra == filters
    }

    function makeUnfilteredConsult() {
        // TODO: Implement Consult to DDBB where obra == filters
    }



}