package com.sumit.cab.smartcab;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.util.Iterator;

import javax.net.ssl.HttpsURLConnection;

public class PriceListActivity extends AppCompatActivity {
    private String Json = "";
    private TextView src, dest;
    private TextView duration, distance;
    private TextView ola_price, uber_price, tabcab_price;
    private SharedPreferences sharedpreferences;
    private Button olaButton, uberButton, tabcabButton;
    Toolbar toolbar;
    private String em;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_price_list);
        sharedpreferences = getSharedPreferences("SmartCab", Context.MODE_PRIVATE);
        final SharedPreferences.Editor editor = sharedpreferences.edit();

        toolbar = (Toolbar) findViewById(R.id.homeToolbar);
        toolbar.setTitle("SmartCab");
        setSupportActionBar(toolbar);
        toolbar.setTitleTextColor(Color.WHITE);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);


        String uemail = sharedpreferences.getString("email", null);
        String id = sharedpreferences.getString("id", null);
        String origin = sharedpreferences.getString("source", null);
        String destination = sharedpreferences.getString("destination", null);

        olaButton = (Button) findViewById(R.id.ola);
        uberButton = (Button) findViewById(R.id.uber);
        tabcabButton = (Button) findViewById(R.id.tabCar);

        src = (TextView) findViewById(R.id.src);
        src.setText(sharedpreferences.getString("or", null));

        dest = (TextView) findViewById(R.id.dest);
        dest.setText(sharedpreferences.getString("desti", null));

        distance = (TextView) findViewById(R.id.distance);
        distance.setText(sharedpreferences.getString("distance", null) + " KM");

        duration = (TextView) findViewById(R.id.duration);
        Log.e("dur : ",sharedpreferences.getString("duration",null));
        duration.setText(sharedpreferences.getString("duration", null) + " Min");

        ola_price = (TextView) findViewById(R.id.ola_price);
        ola_price.setText(sharedpreferences.getString("ola_rate", null) + " Rs");

        uber_price = (TextView) findViewById(R.id.uber_price);
        uber_price.setText(sharedpreferences.getString("uber_rate", null) + " Rs");

        tabcab_price = (TextView) findViewById(R.id.tabCar_price);
        tabcab_price.setText(sharedpreferences.getString("tabcabrate", null) + " Rs");


        olaButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String uemail = sharedpreferences.getString("email", null);
                String idUber = sharedpreferences.getString("id_uber_parser", null);
                String idOla = sharedpreferences.getString("id_parse", null);
                String idtabCar = sharedpreferences.getString("id_tabcab_parser", null);
                String id = "";
                if (idOla != null) {
                    id = idOla;
                    new SendPostRequestForPrice(uemail, id, editor).execute();
                } else {
                    Toast.makeText(getBaseContext(), "Please Select your selected taxi ", Toast.LENGTH_SHORT).show();
                }

            }
        });
        uberButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String uemail = sharedpreferences.getString("email", null);
                String idUber = sharedpreferences.getString("id_uber_parser", null);
                String idOla = sharedpreferences.getString("id_parse", null);
                String idtabCar = sharedpreferences.getString("id_tabcab_parser", null);
                String id = "";
                if (idtabCar != null) {
                    id = idtabCar;
                    new SendPostRequestForPrice(uemail, id, editor).execute();
                } else {
                    Toast.makeText(getBaseContext(), "Please Select your selected taxi ", Toast.LENGTH_SHORT).show();
                }
            }
        });
        tabcabButton.setOnClickListener(new View.OnClickListener() {
            String uemail = sharedpreferences.getString("email", null);
            String idUber = sharedpreferences.getString("id_uber_parser", null);
            String idOla = sharedpreferences.getString("id_parse", null);
            String idtabCar = sharedpreferences.getString("id_tabcab_parser", null);
            String id = "";

            @Override
            public void onClick(View v) {
                if (idUber != null) {
                    id = idUber;
                    new SendPostRequestForPrice(uemail, id, editor).execute();
                } else {
                    Toast.makeText(getBaseContext(), "Please Select your selected taxi ", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }


    public class SendPostRequestForPrice extends AsyncTask<String, Void, String> {
        ProgressDialog pr;
        private String umail;
        private String tid;
        private SharedPreferences.Editor editor;

        SendPostRequestForPrice(String umail, String tid, SharedPreferences.Editor editor) {
            this.umail = umail;
            this.tid = tid;
            this.editor = editor;
        }

        protected void onPreExecute() {
            pr = new ProgressDialog(PriceListActivity.this);
            pr.setMessage("Retrieving data...");
            pr.show();
        }

        protected String doInBackground(String... arg0) {

            try {

                URL url = new URL("http://webstermind.000webhostapp.com/api/getRide.php"); // here is your URL path http://techprominds.esy.es/login.php

                JSONObject postDataParams = new JSONObject();
                postDataParams.put("email", umail);
                postDataParams.put("fid", tid);
                Log.e("params", postDataParams.toString());

                HttpURLConnection conn = (HttpURLConnection) url.openConnection();
                conn.setReadTimeout(15000 /* milliseconds */);
                conn.setConnectTimeout(15000 /* milliseconds */);
                conn.setRequestMethod("POST");
                conn.setDoInput(true);
                conn.setDoOutput(true);

                OutputStream os = conn.getOutputStream();
                BufferedWriter writer = new BufferedWriter(
                        new OutputStreamWriter(os, "UTF-8"));
                writer.write(getPostDataString(postDataParams));

                writer.flush();
                writer.close();
                os.close();

                int responseCode = conn.getResponseCode();

                if (responseCode == HttpsURLConnection.HTTP_OK) {

                    BufferedReader in = new BufferedReader(new
                            InputStreamReader(
                            conn.getInputStream()));

                    StringBuffer sb = new StringBuffer("");
                    String line = "";

                    while ((line = in.readLine()) != null) {

                        sb.append(line);
                        break;
                    }
                    Json = sb.toString();
                    Log.e("Json", sb.toString());
                    in.close();
                    return sb.toString();

                } else {
                    return new String("false : " + responseCode);
                }
            } catch (Exception e) {
                return new String("Exception: " + e.getMessage());
            }

        }

        @Override
        protected void onPostExecute(String result) {
            result = result.trim();
            JSONObject jsonObj = null;
            try {
                jsonObj = new JSONObject(result);
                JSONObject response = jsonObj.getJSONObject("response");
                int hasError = response.getInt("hasError");
                if (hasError == 0) {
                    String res = response.getString("msg");
                    Toast.makeText(getBaseContext(),""+res,Toast.LENGTH_SHORT).show();
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }

            pr.dismiss();
        }
    }

    public String getPostDataString(JSONObject params) throws Exception {

        StringBuilder result = new StringBuilder();
        boolean first = true;

        Iterator<String> itr = params.keys();

        while (itr.hasNext()) {

            String key = itr.next();
            Object value = params.get(key);

            if (first)
                first = false;
            else
                result.append("&");

            result.append(URLEncoder.encode(key, "UTF-8"));
            result.append("=");
            result.append(URLEncoder.encode(value.toString(), "UTF-8"));

        }
        return result.toString();
    }

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
}