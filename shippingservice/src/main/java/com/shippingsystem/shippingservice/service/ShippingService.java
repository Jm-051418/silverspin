package com.shippingsystem.shippingservice.service;

import java.util.List;
import java.util.Random;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
// import org.springframework.web.client.RestTemplate;
// import com.shippingsystem.shippingservice.model.Orders;
import com.shippingsystem.shippingservice.model.Shippings;
import com.shippingsystem.shippingservice.repository.ShippingRepository;

@Service
public class ShippingService {

    @Autowired
    ShippingRepository shippingRepository;

    // private final RestTemplate restTemplate;
    // Orders order;

    // public ShippingService(RestTemplate restTemplate) {
    //     this.restTemplate = restTemplate;
    // }

    public List<Shippings> getAllShipping() {
        return shippingRepository.findAll();
    }

    public String createShipping(Shippings shipping) {
        String trackingNumber = "TRK-" + new Random().nextInt(9999) + "-" + new Random().nextInt(9999);

        Shippings completeShipping = new Shippings(shipping.getOrderId(), shipping.getName(), shipping.getEmail(), shipping.getPhone(),shipping.getAddress(), trackingNumber);
        shippingRepository.save(completeShipping);

        return trackingNumber;
    }

    public Shippings getShippingById(int id) {
        return shippingRepository.findById(id).orElse(new Shippings());
    }

    // public void getOrderById(int id) {
    //     String url = "http://localhost:8080/order/" + id;
    //     order = restTemplate.getForObject(url, Orders.class);
    // }
    
}
