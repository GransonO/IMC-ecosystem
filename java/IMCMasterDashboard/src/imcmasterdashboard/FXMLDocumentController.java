/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package imcmasterdashboard;

import java.net.URL;
import java.util.ResourceBundle;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.web.WebEngine;
import javafx.scene.web.WebView;

/**
 *
 * @author Granson
 */
public class FXMLDocumentController implements Initializable {
    
    @FXML
    final private WebView webview = new WebView();
           
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        WebEngine engine = webview.getEngine();
        engine.load("https://www.google.com");
    }    
}
