package com.ordersystem.orderservice.controller;

import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import com.ordersystem.orderservice.model.Orders;
import com.ordersystem.orderservice.service.OrderService;
import java.util.List;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.PutMapping;




@RestController
@RequestMapping("/")
public class OrderController {

    @Autowired
    OrderService orderService;
    
    // @GetMapping("/main")
    // public String introduction() {
    //     return "Welcome";
    // }

    @GetMapping("/orders")   
    public List<Orders> getOrders() {
        return orderService.getOrders();
    }

    // @GetMapping("/order/{id}")
    // public Orders getOrderById(@PathVariable int id) {
    //     return orderService.getOrderById(id);
    // }
    
    // @GetMapping("/shippings")
    // public List<Orders> getOrderByStatus() {
    //     return orderService.getOrderByStatus();
    // }

    @PostMapping("/orders")
    public void createOrder(@RequestBody Orders order) {
        orderService.createOrder(order);
    }

    @PutMapping("/orders")
    public void editOrder(@RequestBody Orders order) {
        orderService.editOrder(order);
    }

    // @PutMapping("/checkout")
    // public void checkoutOrder(@RequestBody Orders order) {
    //     orderService.checkoutOrder(order);
    // }

    @PutMapping("/update")
    public void updateOrder(@RequestBody Orders order) {
        orderService.updateOrder(order);
    }


    @DeleteMapping("/orders/{id}")
    public void deleteOrder(@PathVariable int id) {
        orderService.deleteOrder(id);
    }
    
    
}
