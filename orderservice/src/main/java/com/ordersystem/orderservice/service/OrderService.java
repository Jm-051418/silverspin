package com.ordersystem.orderservice.service;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Service;

import com.ordersystem.orderservice.model.Orders;
import com.ordersystem.orderservice.repository.OrderRepository;

@Service
public class OrderService {

    @Autowired
    OrderRepository orderRepository;

    public List<Orders> getOrders(){
        return orderRepository.findAll();
    }

    public Orders getOrderById(int id) {
        return orderRepository.findById(id).orElse(new Orders());
    }

    public void createOrder(Orders order) {
        orderRepository.save(order);
    }

    public void editOrder(Orders order) {
        orderRepository.save(order);
    }

    public void deleteOrder(int id) {
        orderRepository.deleteById(id);
    }

    // public void checkoutOrder(Orders order) {
    //     orderRepository.save(order);
    // }

    // public List<Orders> getOrderByStatus() {
    //     return orderRepository.findByOrderStatusNot("Pending");
    // }

    public void updateOrder(Orders order) {
        int id = order.getId();
        String trackingNumber = order.getTrackingNumber();

        orderRepository.findById(id)
        .map(o -> {
            o.setStatus("Shipped");
            o.setTrackingNumber(trackingNumber);
            orderRepository.save(o); 
            return ResponseEntity.ok();
        });
    }
}
