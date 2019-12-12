package main

import (
	"fmt"
	"os"
	"runtime"
	"time"
)
var week time.Duration;

func main() {
	t := time.Now()
	fmt.Println(t)
	fmt.Printf("%4d-%02d-%02d %2d:%2d:%2d\n", t.Year(), t.Month(), t.Day(), t.Hour(), t.Minute(), t.Second())
	t = time.Now().UTC()
	fmt.Println(t)
	fmt.Println(time.Now())
	week = 60 * 60 * 24 * 7 * 1e9
	week_from_now := t.Add(week)
	fmt.Println(week_from_now)
	fmt.Println(t.Format(time.RFC822))
	fmt.Println(t.Format(time.ANSIC))
	fmt.Println(t.Format("21 Dec 2011 08:52"))
	s := t.Format("20111221")
	fmt.Println(t, "=>", s)
	fmt.Println("pointer")
	var i1 = 5
	fmt.Printf("An integer: %d, it's location in memory: %p\n", i1, &i1)
	var intP *int
	intP = &i1
	fmt.Printf("The value at memory location %p is %d\n", intP, *intP)
	sp := "good bye"
	var p *string = &sp
	*p = "ciao"
	fmt.Printf("Here is the pointer p: %p\n", p)
	fmt.Printf("Here is the string *p: %s\n", *p)
	fmt.Printf("Here is the string s: %s\n", sp)
	var goos string = runtime.GOOS
	fmt.Printf("The operating system is: %s\n", goos)
	path := os.Getenv("PATH")
	fmt.Printf("Path is %s\n", path)
}