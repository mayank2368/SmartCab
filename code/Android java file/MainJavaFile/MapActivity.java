/*
 * Copyright (C) 2015 Google Inc. All Rights Reserved.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

package com.sumit.cab.smartcab;

import com.google.android.gms.common.api.Status;
import com.google.android.gms.location.places.Place;
import com.google.android.gms.location.places.ui.PlaceAutocompleteFragment;
import com.google.android.gms.location.places.ui.PlaceSelectionListener;
import com.google.android.gms.maps.model.LatLngBounds;

import android.app.AlertDialog;
import android.common.activities.SampleActivityBase;
import android.common.logger.Log;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.res.Resources;
import android.graphics.Color;
import android.net.Uri;
import android.os.Bundle;

import android.support.v7.widget.Toolbar;
import android.text.Html;
import android.text.Spanned;
import android.text.TextUtils;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;


public class MapActivity extends SampleActivityBase implements PlaceSelectionListener {

    private TextView mPlaceDetailsText;

    private TextView mPlaceAttribution;

    private TextView mPlaceDetailsText_dest;

    private Button CompTaxi;
    Toolbar toolbar;

    private ImageButton about_us, cont_us;

    private SharedPreferences sharedpreferences;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_map);
        Log.e("Back Button : ","button");
        sharedpreferences = getSharedPreferences("SmartCab", Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedpreferences.edit();
        editor.putString("source",null);
        editor.putString("destination",null);
        editor.commit();

        String email = sharedpreferences.getString("email",null);
        if(email == null) {
            Log.e("Null data : ","email");
            startActivity(new Intent(getBaseContext(), LoginActivity.class));
        }

        CompTaxi = (Button) findViewById(R.id.Search);
        toolbar = (Toolbar) findViewById(R.id.homeToolbar);
        toolbar.setTitle("SmartCab");
        setSupportActionBar(toolbar);
        toolbar.setTitleTextColor(Color.WHITE);

        about_us = (ImageButton) findViewById(R.id.about_us);
        cont_us = (ImageButton) findViewById(R.id.con_us);

        about_us.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(getBaseContext(),AboutUsActivity.class));
            }
        });

        cont_us.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(getBaseContext(),ContActivity.class));
            }
        });
        // Retrieve the PlaceAutocompleteFragment.
        PlaceAutocompleteFragment autocompleteFragment = (PlaceAutocompleteFragment)
                getFragmentManager().findFragmentById(R.id.autocomplete_fragment);

        PlaceAutocompleteFragment autocompleteFragment2 = (PlaceAutocompleteFragment)
                getFragmentManager().findFragmentById(R.id.autocomplete_fragment2);

        // Register a listener to receive callbacks when a place has been selected or an error has
        // occurred.
        autocompleteFragment.setOnPlaceSelectedListener(this);
        autocompleteFragment2.setOnPlaceSelectedListener(this);

        // Retrieve the TextViews that will display details about the selected place.
        mPlaceDetailsText = (TextView) findViewById(R.id.place_details);
        //mPlaceAttribution = (TextView) findViewById(R.id.place_attribution);

        mPlaceDetailsText_dest = (TextView) findViewById(R.id.place_details_dest);

        CompTaxi.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                String source = sharedpreferences.getString("source",null);
                String destination = sharedpreferences.getString("destination",null);
                if(source != null && destination != null){
                    Log.e("source ", source);
                    Log.e("destination",destination);
                    startActivity(new Intent(getBaseContext(),TaxiListDemoActivity.class));
                    //finish();
                }
                else {
                    Toast.makeText(getBaseContext(), "Please Select your source and destionation", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

    /**
     * Callback invoked when a place has been selected from the PlaceAutocompleteFragment.
     */
    @Override
    public void onPlaceSelected(Place place) {
        Log.i(TAG, "Place Selected: " + place.getName());

        SharedPreferences.Editor editor = sharedpreferences.edit();
        String source = sharedpreferences.getString("source",null);
        if(source==null){
            editor.putString("source",place.getLatLng().toString().substring(place.getLatLng().toString().indexOf('(')+1,place.getLatLng().toString().indexOf(')')));
            Log.e("Latlang : ",place.getLatLng().toString().substring(place.getLatLng().toString().indexOf('(')+1,place.getLatLng().toString().indexOf(')')));
        }
        else{
            editor.putString("destination",place.getLatLng().toString().substring(place.getLatLng().toString().indexOf('(')+1,place.getLatLng().toString().indexOf(')')));
            Log.e("Latlang : ",place.getLatLng().toString().substring(place.getLatLng().toString().indexOf('(')+1,place.getLatLng().toString().indexOf(')')));
        }
        editor.commit();
        // Format the returned place's details and display them in the TextView.
        /*
        mPlaceDetailsText.setText(place.getLatLng()+"");

        mPlaceDetailsText_dest.setText(place.getLatLng()+"");*/
    }

    /**
     * Callback invoked when PlaceAutocompleteFragment encounters an error.
     */
    @Override
    public void onError(Status status) {
        Log.e(TAG, "onError: Status = " + status.toString());

        Toast.makeText(this, "Place selection failed: " + status.getStatusMessage(),
                Toast.LENGTH_SHORT).show();
    }

    /**
     * Helper method to format information about a place nicely.
     */
    /*
    private static Spanned formatPlaceDetails(Resources res, CharSequence name, String id,
                                              CharSequence address, CharSequence phoneNumber, Uri websiteUri) {
        Log.e(TAG, res.getString(R.string.place_details, name, id, address, phoneNumber,
                websiteUri));
        return Html.fromHtml(res.getString(R.string.place_details, name, id, address, phoneNumber,
                websiteUri));

    }*/

    @Override
    public boolean onCreateOptionsMenu(Menu menu){
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.hometoolbar, menu);
        return true;
    }


    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch(item.getItemId()){
            case R.id.logout:
                String email = sharedpreferences.getString("email",null);
                SharedPreferences.Editor editor = sharedpreferences.edit();
                editor.putString("email", null);
                editor.putString("password", null);
                editor.commit();
                Toast.makeText(getBaseContext(),"Logout successfully done",Toast.LENGTH_SHORT).show();
                startActivity(new Intent(getBaseContext(),MainActivity.class));
                finishAffinity();
                break;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onBackPressed() {
        if (getFragmentManager().getBackStackEntryCount() > 0) {
            getFragmentManager().popBackStackImmediate();
        } else {
            new AlertDialog.Builder(this)
                    .setTitle(R.string.exit)
                    .setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            finish();
                        }
                    })
                    .setIcon(R.mipmap.ic_launcher)
                    .setNegativeButton(R.string.no, new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {

                        }
                    })
                    .setMessage(R.string.confirmExit)
                    .show();
        }
    }
}