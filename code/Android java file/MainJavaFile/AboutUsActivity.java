package com.sumit.cab.smartcab;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.support.v4.app.ActivityCompat;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.widget.Toast;

public class AboutUsActivity extends AppCompatActivity {

    private Toolbar toolbar;
    private SharedPreferences sharedpreferences;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_about_us);

        sharedpreferences = getSharedPreferences("SmartCab", Context.MODE_PRIVATE);

        toolbar = (Toolbar) findViewById(R.id.homeToolbar);
        toolbar.setTitle("SmartCab");
        setSupportActionBar(toolbar);
        toolbar.setTitleTextColor(Color.WHITE);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

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
