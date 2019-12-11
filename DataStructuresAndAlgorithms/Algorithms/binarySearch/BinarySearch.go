package main

import "fmt"
import "C"

func main() {
	println("Hello", "world")
	fmt.Print("Hello")
	fmt.Print(C.random())
}
