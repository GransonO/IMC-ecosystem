/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package imcmasterdashboard;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.layout.AnchorPane;
import javafx.scene.web.WebEngine;
import javafx.scene.web.WebView;
import javafx.stage.Stage;

/**
 *
 * @author Granson
 */
public class IMCMasterDashboard extends Application {
    
    @Override
    public void start(Stage stage) throws Exception {
        AnchorPane root = new AnchorPane();
        
        WebView webview = new WebView();
        
        WebEngine engine = webview.getEngine();
        engine.load("https://indulgecard.co.ke/admin/jade_col/ini/");

        root.getChildren().add(webview);
        
        Scene scene = new Scene(root, 1000, 750);
        stage.setScene(scene);
        stage.show();
    }

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        launch(args);
    }
    
}
