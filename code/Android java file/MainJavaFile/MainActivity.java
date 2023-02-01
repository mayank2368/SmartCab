package com.sumit.cab.smartcab;

import android.Manifest;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.support.v4.app.ActivityCompat;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

public class MainActivity extends AppCompatActivity {
    static final int MY_REQUEST=10;
    private Button getStated;
    private SharedPreferences sharedpreferences;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        if(ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED
                    || ActivityCompat.checkSelfPermission(this,Manifest.permission.CALL_PHONE) != PackageManager.PERMISSION_GRANTED
                || ActivityCompat.checkSelfPermission(this,Manifest.permission.INTERNET) != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION,
                    Manifest.permission.INTERNET,
                    Manifest.permission.CALL_PHONE}, MY_REQUEST);
        }

        final SharedPreferences sharedPreferences = getSharedPreferences("SmartCab", Context.MODE_PRIVATE);

        getStated = (Button) findViewById(R.id.getStated);

        getStated.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String email = sharedPreferences.getString("email",null);
                if(email == null) {
                    startActivity(new Intent(getBaseContext(), LoginActivity.class));
                    finish();
                }
                else{
                    startActivity(new Intent(getBaseContext(), MapActivity.class));
                    finish();
                }
            }
        });
    }
}
