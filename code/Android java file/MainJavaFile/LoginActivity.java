package com.sumit.cab.smartcab;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

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

public class LoginActivity extends AppCompatActivity {

    private Button Login;
    private EditText email;
    private EditText password;

    private SharedPreferences sharedpreferences;
    private TextView textView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        sharedpreferences = getSharedPreferences("SmartCab", Context.MODE_PRIVATE);

        textView = (TextView) findViewById(R.id.textView);
        Login = (Button) findViewById(R.id.login);
        email = (EditText) findViewById(R.id.email);
        password = (EditText) findViewById(R.id.password);
        final SharedPreferences.Editor editor = sharedpreferences.edit();

        Login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String uemail = email.getText().toString().trim();
                String pwd = password.getText().toString().trim();
                new SendPostRequest(uemail, pwd, editor).execute();
            }
        });

        textView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(getBaseContext(),SignUpActivity.class));
                finish();
            }
        });
    }


    public class SendPostRequest extends AsyncTask<String, Void, String> {
        ProgressDialog pr;
        private String umail;
        private String pwd;
        private SharedPreferences.Editor editor;

        SendPostRequest(String umail, String pwd, SharedPreferences.Editor editor) {
            this.umail = umail;
            this.pwd = pwd;
            this.editor = editor;
        }

        protected void onPreExecute() {
            pr = new ProgressDialog(LoginActivity.this);
            pr.setMessage("Retrieving data...");
            pr.show();
        }

        protected String doInBackground(String... arg0) {

            try {

                URL url = new URL("http://webstermind.000webhostapp.com/api/login.php"); // here is your URL path http://techprominds.esy.es/login.php

                JSONObject postDataParams = new JSONObject();
                postDataParams.put("email", umail);
                postDataParams.put("password", pwd);
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
            if (result.equals("true")) {
                editor.putString("email", umail);
                editor.putString("password", pwd);
                editor.commit();
                //Toast.makeText(getApplicationContext(), "Login Sucessfully!", Toast.LENGTH_SHORT).show();
                startActivity(new Intent(getBaseContext(), MapActivity.class));
                finish();
            } else {
                Toast.makeText(getApplicationContext(), result,
                        Toast.LENGTH_LONG).show();
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

}
