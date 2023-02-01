package com.sumit.cab.smartcab;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.AsyncTask;
import android.support.v4.app.ActivityCompat;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

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

public class TaxiListDemoActivity extends AppCompatActivity {
    private String Json = "";
    private Toolbar toolbar;

    private SharedPreferences sharedPreferences;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_taxi_list_demo);

        sharedPreferences = getSharedPreferences("SmartCab", Context.MODE_PRIVATE);

        SharedPreferences.Editor editor = sharedPreferences.edit();
        /*
            Null SharedPrefernces
         */
        /*
        editor.putString("or", null);
        editor.putString("destination", null);
        editor.putString("distance", null);
        editor.putString("duration", null);
        editor.putString("id_parse", null);
        editor.putString("ola_rate", null);
        editor.putString("id_uber_parser", null);
        editor.putString("uber_rate", null);
        editor.putString("id_tabcab_parser", null);
        editor.putString("tabcabrate", null);
        editor.commit();*/

        toolbar = (Toolbar) findViewById(R.id.homeToolbar);
        setSupportActionBar(toolbar);
        toolbar.setTitle("SmartCab");
        toolbar.setTitleTextColor(Color.WHITE);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
    }
    public void onClick(View view){
        int id = -1;
        if(view.getId()==R.id.taxi){
            id=1;
        }
        if(view.getId()==R.id.Sedan){
            id=2;
        }
        if(view.getId()== R.id.SUV){
            id=3;
        }
        if(view.getId() == R.id.LUX){
            id=4;
        }
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString("id",id+"");
        editor.commit();

        String email = sharedPreferences.getString("email",null);
        String or = sharedPreferences.getString("source",null);
        String de = sharedPreferences.getString("destination",null);
        if(email != null && or != null && de != null) {
            new SendPostRequest(email, id + "", or, de, editor).execute();
        }
        else{
            Toast.makeText(getBaseContext(),"Some Attribute is missing ! ",Toast.LENGTH_SHORT).show();
        }
    }

    public class SendPostRequest extends AsyncTask<String, Void, String> {
        ProgressDialog pr;
        private String umail;
        private String tid;
        private String or;
        private String de;
        private SharedPreferences.Editor editor;

        SendPostRequest(String umail, String tid, String or, String de, SharedPreferences.Editor editor) {
            this.umail = umail;
            this.tid = tid;
            this.or = or;
            this.de = de;
            this.editor = editor;
        }

        protected void onPreExecute() {
            pr = new ProgressDialog(TaxiListDemoActivity.this);
            pr.setMessage("Retrieving data...");
            pr.show();
        }

        protected String doInBackground(String... arg0) {

            try {

                URL url = new URL("http://webstermind.000webhostapp.com/api/getPrice.php"); // here is your URL path http://techprominds.esy.es/login.php

                JSONObject postDataParams = new JSONObject();
                postDataParams.put("email", umail);
                postDataParams.put("tid", tid);
                postDataParams.put("origin", or);
                postDataParams.put("destination", de);
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
                    Log.e("Jsson", sb.toString());
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
            if (!Json.equals("")) {
                try {
                    JSONObject jsonObj = new JSONObject(result);
                    JSONObject response = jsonObj.getJSONObject("response");
                    int hasError = response.getInt("hasError");
                    if (hasError == 0) {
                        Log.e("Display", "Message");
                        JSONObject msg = response.getJSONObject("msg");
                        String origin = msg.getString("origin");
                        editor.putString("or", origin);
                        Log.e("Or : ",origin);
                        editor.commit();

                        String destination1 = msg.getString("destination");
                        editor.putString("desti", destination1 + "");
                        Log.e("desti : ",destination1);
                        editor.commit();

                        String distance1 = msg.getInt("distance") + "";
                        editor.putString("distance", distance1);
                        Log.e("desti : ",destination1);
                        editor.commit();

                        String duration1 = msg.getInt("duration") + "";
                        editor.putString("duration", duration1);
                        Log.e("duration : ",duration1);
                        editor.commit();

                        JSONObject ola = msg.getJSONObject("ola");
                        String olaid = ola.getString("id");
                        editor.putString("id_parse", olaid);
                        editor.commit();

                        String olarate = ola.getString("rate");
                        editor.putString("ola_rate", olarate);
                        editor.commit();


                        JSONObject uber = msg.getJSONObject("uber");
                        String uberid = uber.getString("id");
                        editor.putString("id_uber_parser", uberid);
                        editor.commit();

                        String uberrate = uber.getString("rate");
                        editor.putString("uber_rate", uberrate);
                        editor.commit();


                        JSONObject tabcab = msg.getJSONObject("tabcab");
                        String tabcabid = tabcab.getString("id");
                        editor.putString("id_tabcab_parser", tabcabid);
                        editor.commit();

                        String tabcabrate = tabcab.getString("rate");
                        editor.putString("tabcabrate", tabcabrate);
                        editor.commit();


//                        editor.commit();

                        // Toast.makeText(getApplicationContext(), "Login Sucessfully!", Toast.LENGTH_SHORT).show();
                        //startActivity(new Intent(getBaseContext(), MapActivity.class));
                        pr.dismiss();
                        startActivity(new Intent(getBaseContext(),PriceListActivity.class));
                        //finish();
                    } else {
                        pr.dismiss();
                        Toast.makeText(getBaseContext(), "Data is invalid : ", Toast.LENGTH_SHORT).show();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
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
                String email = sharedPreferences.getString("email",null);
                SharedPreferences.Editor editor = sharedPreferences.edit();
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
