package com.ordersystem.orderservice.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import com.ordersystem.orderservice.model.Orders;

@Repository
public interface OrderRepository extends JpaRepository<Orders, Integer>{
    // List<Orders> findByOrderStatusNot(String orderStatus);
    
}
