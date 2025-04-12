package com.shippingsystem.shippingservice.controller;

import java.util.List;

// import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import com.shippingsystem.shippingservice.model.Shippings;
import com.shippingsystem.shippingservice.service.ShippingService;

@RestController
@RequestMapping("/")
public class ShippingController {

    @Autowired
    ShippingService shippingService;
    
    @GetMapping("/shippings")
    public List<Shippings> getAllShipping() {
        return shippingService.getAllShipping();
    }

    // @GetMapping("/shippings/{id}")
    // public void getOrderById(@PathVariable int id) {
    //     shippingService.getOrderById(id);
    // }

    @GetMapping("/shipping/{id}")
    public Shippings getShippingById(@PathVariable int id) {
        return shippingService.getShippingById(id);
    }

    // @PostMapping("/shippings")
    // public void createShipping(@RequestBody Shippings shipping) {
    //     shippingService.createShipping(shipping);
    // }

    @PostMapping("/shippings")
    public String createShipping(@RequestBody Shippings shipping) {
        String trackingNumber = shippingService.createShipping(shipping);
        return trackingNumber;
    }

    
    
}
